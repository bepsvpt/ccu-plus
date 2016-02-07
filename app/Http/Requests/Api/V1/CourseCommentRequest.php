<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class CourseCommentRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comment_id' => 'sometimes|exists:comments,id',
            'content' => 'required|string|max:3000',
            'anonymous' => 'boolean',
        ];
    }
}
