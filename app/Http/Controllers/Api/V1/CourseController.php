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

        $courses = Cache::tags('course')->remember($key, Course::MINUTES_QUARTER_DAY, function () use ($request) {
            $query = Course::with(['dimension', 'professors'])
                ->withCount('comments')
                ->orderBy('department_id')
                ->orderBy('code')
                ->orderBy('semester_id', 'desc');

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

            $courses = $query->get()->groupBy('code')->map(function ($course) {
                $professors = $course->pluck('professors')->collapse()->unique('id')->values();

                $course->first()->offsetUnset('professors');

                $course->first()->professors = $professors;

                return $course->first();
            });

            return $courses->values();
        });

        return $this->setData($courses)->responseOk();
    }

    public function show($code)
    {
        $course = Cache::tags('course')->remember("info-{$code}", Course::MINUTES_PER_MONTH, function () use ($code) {
            return Course::with(['semester', 'professors'])
                ->where('code', $code)
                ->orderBy('semester_id', 'desc')
                ->get();
        });

        return $this->setData($course)->responseOk();
    }
}
