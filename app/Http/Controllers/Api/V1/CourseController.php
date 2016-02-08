<?php

namespace App\Http\Controllers\Api\V1;

use App\Ccu\Course;
use App\Ccu\General\Category;
use Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CourseController extends ApiController
{
    public function search(Request $request)
    {
        $key = 'search-'.sha1(implode('|', $request->only(['college', 'department_id', 'keyword'])));

        $courses = Cache::tags('course')->remember($key, Course::MINUTES_PER_MONTH, function () use ($request) {
            $query = Course::with(['semester', 'dimension', 'professors'])
                ->groupBy('series_id')
                ->orderBy('semester_id', 'desc')
                ->orderBy('department_id')
                ->orderBy('code');

            if ($request->has('department_id')) {
                $query = $query->where('department_id', $request->input('department_id'));
            } elseif ($request->has('college')) {
                $query = $query->whereIn(
                    'department_id',
                    explode(',', Category::getCategories('college', $request->input('college'))->getAttribute('remark'))
                );
            }

            if ($request->has('keyword')) {
                $query = $query->where(function (Builder $query) use ($request) {
                    $query->where('code', 'like', '%'.$request->input('keyword').'%')
                        ->orWhere('name', 'like', '%'.$request->input('keyword').'%');
                });
            }

            return $query->get();
        });

        return $this->setData($courses)->responseOk();
    }

    public function show($seriesId)
    {
        $course = Cache::tags('course')->remember("info-{$seriesId}", Course::MINUTES_PER_MONTH, function () use ($seriesId) {
            return Course::with(['semester', 'professors'])
                ->where('series_id', $seriesId)
                ->orderBy('semester_id', 'desc')
                ->get();
        });

        return $this->setData($course)->responseOk();
    }
}
