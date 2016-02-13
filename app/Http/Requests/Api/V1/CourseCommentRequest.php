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
        /** todo: Fix professor bug */

        return [
            'comment_id' => 'sometimes|exists:comments,id',
            'content' => 'required|string|max:3000',
            'professor' => 'required|array',
            'professor.*' => 'required|exists:categories,id,category,professor',
            'anonymous' => 'boolean',
        ];
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'professor.*.required' => '請選擇授課教授',
            'professor.*.exists' => '您所選擇的授課教授不存在',
        ];
    }
}
