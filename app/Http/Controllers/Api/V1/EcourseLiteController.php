<?php

namespace App\Http\Controllers\Api\V1;

use App\Ccu\Core\Entity;
use App\Ccu\EcourseLite\Announcement;
use App\Ccu\EcourseLite\Attachment;
use App\Ccu\EcourseLite\Course;
use App\Ccu\EcourseLite\Grade;
use App\Ccu\EcourseLite\Homework;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EcourseLiteController extends ApiController
{
    /**
     * 課程 id.
     *
     * @var string
     */
    protected $courseId = '';

    /**
     * Ecourse 課程列表.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function courseList(Request $request)
    {
        $key = 'course-list-'.md5($request->user()->getAuthIdentifier());

        $courses = Cache::tags('ecourse-lite')->remember($key, Carbon::now()->endOfDay(), function () {
            return Course::lists();
        });

        return $this->setData($courses)->responseOk();
    }

    /**
     * 課程內容.
     *
     * @param Request $request
     * @param string $courseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function courseContent(Request $request, $courseId)
    {
        $this->courseId = $courseId;

        $data = [
            'announcements' => $this->announcements(),
            'attachments' => $this->attachments(),
            'homework' => $this->homework($request),
            'grades' => $this->grades($request),
        ];

        return $this->setData($data)->responseOk();
    }

    /**
     * 公告列表.
     *
     * @return array
     */
    protected function announcements()
    {
        $key = 'announcements-'.$this->courseId;

        return Cache::tags('ecourse-lite')->remember($key, 15, function () {
            return Announcement::lists($this->courseId);
        });
    }

    /**
     * 授課教材.
     *
     * @return array
     */
    protected function attachments()
    {
        $key = 'attachments-'.$this->courseId;

        return Cache::tags('ecourse-lite')->remember($key, Carbon::now()->endOfDay(), function () {
            return Attachment::lists($this->courseId);
        });
    }

    /**
     * 成績資料.
     *
     * @param Request $request
     * @return array
     */
    protected function grades(Request $request)
    {
        $key = 'grades-'.md5($request->user()->getAuthIdentifier()).'-'.$this->courseId;

        return Cache::tags('ecourse-lite')->remember($key, Carbon::now()->endOfDay(), function () {
            return Grade::lists($this->courseId);
        });
    }

    /**
     * 作業列表.
     *
     * @param Request $request
     * @return array
     */
    protected function homework(Request $request)
    {
        $key = 'homework-'.md5($request->user()->getAuthIdentifier()).'-'.$this->courseId;

        return Cache::tags('ecourse-lite')->remember($key, Entity::MINUTES_QUARTER_DAY, function () {
            return Homework::lists($this->courseId);
        });
    }
}
