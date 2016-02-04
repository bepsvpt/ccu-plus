<?php

namespace App\Http\Controllers\Api\V1;

use App\Ccu\Course;
use Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CourseController extends ApiController
{
    public function search(Request $request)
    {
        $key = 'course-search-'.sha1($request->input('department_id').'|'.$request->input('keyword'));

        $courses = Cache::remember($key, 10, function () use ($request) {
            $query = Course::with(['semester', 'department', 'dimension', 'professors'])
                ->groupBy('series_id')
                ->orderBy('semester_id', 'desc');

            if ($request->has('department_id')) {
                $query = $query->where('department_id', $request->input('department_id'));
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
        $course = Cache::remember("course-info-{$seriesId}", Course::MINUTES_PER_MONTH, function () use ($seriesId) {
            return Course::with(['semester', 'department', 'professors'])
                ->where('series_id', $seriesId)
                ->orderBy('semester_id', 'desc')
                ->get();
        });

        return $this->setData($course)->responseOk();
    }
}
