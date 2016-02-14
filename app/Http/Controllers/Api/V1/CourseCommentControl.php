<?php

namespace App\Http\Controllers\Api\V1;

use App\Ccu\Course;
use App\Ccu\General\Comment;
use App\Ccu\General\Like;
use App\Http\Requests\Api\V1;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseCommentControl extends ApiController
{
    public function index($code)
    {
        $course = Course::where('code', $code)->first();

        if (is_null($course)) {
            return $this->responseNotFound();
        }

        $comments = $course->comments()->with(['comments', 'professors'])->latest()->get();

        return $this->setData($comments)->responseOk();
    }

    public function store(V1\CourseCommentRequest $request, $code)
    {
        $course = Course::where('code', $code)->first();

        if (is_null($course)) {
            return $this->responseNotFound();
        }

        $model = new Comment([
            'user_id' => $request->user()->getAttribute('id'),
            'comment_id' => $request->input('comment_id'),
            'content' => $request->input('content'),
            'anonymous' => $request->input('anonymous') ?? false,
        ]);

        if (! $request->has('comment_id')) {
            $comment = $course->comments()->save($model);

            $comment->professors()->sync($request->input('professor'));

            $comment = $comment->fresh()->load(['comments', 'professors']);
        } else {
            $model->save();

            $comment = $model->fresh()->load(['comments']);
        }

        return $this->setData($comment)->responseCreated();
    }

    public function waterfall(Request $request)
    {
        $key = 'comment-waterfall-'.$request->input('id', 0);

        $comments = Cache::tags('course')->remember($key, 5, function () use ($request) {
            Course::setPrimaryKey('code');

            $query = Comment::with(['commentable'])
                ->where('commentable_type', 'course')
                ->whereNull('comment_id')
                ->latest()
                ->take(5);

            if ($request->has('id')) {
                $query = $query->where('id', '<', $request->input('id'));
            }

            return $query->get();
        });

        return $this->setData($comments)->responseOk();
    }

    public function like(Request $request, $code, $commentId)
    {
        $comment = Comment::with(['likes'])->where('id', $commentId)->first();

        if (is_null($comment)) {
            return $this->responseNotFound();
        }

        $index = $comment->getRelation('likes')->search(function ($like) use ($request) {
            return $request->user()->getAttribute('id') === $like->getAttribute('user_id');
        });

        if (false === $index) {
            $comment->likes()->save(new Like([
                'user_id' => $request->user()->getAttribute('id'),
                'created_at' => Carbon::now(),
            ]));

            $comment->increment('likes');
        } else {
            $comment->getRelation('likes')[$index]->delete();

            $comment->decrement('likes');
        }

        return $this->setData($comment->fresh())->responseOk();
    }
}
