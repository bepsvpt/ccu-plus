<?php

namespace App\Http\Controllers\Api\V1;

use App\Ccu\EcourseLite\Announcement;
use App\Ccu\EcourseLite\Course;
use Cache;
use Illuminate\Http\Request;

class EcourseLiteController extends ApiController
{
    /**
     * Ecourse 課程列表.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function courseList(Request $request)
    {
        $key = 'course-list-'.md5($request->user()->getAuthIdentifier());

        $courses = Cache::tags('ecourse-lite')->remember($key, 10, function () {
            return Course::lists();
        });

        return $this->setData($courses)->responseOk();
    }

    /**
     * 課程內容.
     *
     * @param string $courseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function courseContent($courseId)
    {
        $data = [
            'announcements' => $this->announcements($courseId),
            'homework' => [],
            'grades' => [],
            'attachments' => [],
        ];

        return $this->setData($data)->responseOk();
    }

    /**
     * Ecourse 公告.
     *
     * @param string $courseId
     * @return mixed
     */
    protected function announcements($courseId)
    {
        $key = 'announcements-'.$courseId;

        return Cache::tags('ecourse-lite')->remember($key, 10, function () use ($courseId) {
            return Announcement::lists($courseId);
        });
    }
}
