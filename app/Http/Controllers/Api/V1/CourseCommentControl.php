<?php

namespace App\Http\Controllers\Api\V1;

use App\Ccu\Course;
use App\Ccu\General\Comment;
use App\Http\Requests\Api\V1;
use App\Http\Requests\Request;

class CourseCommentControl extends ApiController
{
    public function index($seriesId)
    {
        $course = Course::where('seriesId', $seriesId)->first();

        if (is_null($course)) {
            return $this->responseNotFound();
        }

        $comments = $course->comments()->latest()->paginate(5);

        return $this->setData($comments)->responseOk();
    }

    public function store(V1\CourseCommentRequest $request, $seriesId)
    {
        $course = Course::where('seriesId', $seriesId)->first();

        if (is_null($course)) {
            return $this->responseNotFound();
        }

        $comment = $course->comments()->save(new Comment([
            'user_id' => $request->user()->getAttribute('id'),
            'comment_id' => $request->input('comment_id'),
            'content' => $request->input('content'),
            'anonymous' => $request->input('anonymous'),
        ]));

        return $this->setData($comment)->responseCreated();
    }

    public function wookmark(Request $request)
    {
    }
}
