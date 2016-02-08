<?php

namespace App\Http\Controllers\Api\V1;

use App\Ccu\EcourseLite\Course;
use Cache;
use Illuminate\Http\Request;

class EcourseLiteController extends ApiController
{
    public function courseList(Request $request)
    {
        $key = 'course-list-'.md5($request->user()->getAuthIdentifier());

        $courses = Cache::tags('ecourse-lite')->remember($key, 10, function () {
            return Course::lists();
        });

        return $this->setData($courses)->responseOk();
    }
}
