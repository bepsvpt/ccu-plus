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
        /* todo: Fix professor bug */

        return [
            'comment_id' => 'required_without:professor|exists:comments,id',
            'content' => 'required|string|max:3000',
            'professor' => 'required_without:comment_id|array',
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
            'content.required' => '內容不能留空',
            'professor.*.required' => '請選擇授課教授',
            'professor.*.exists' => '您所選擇的授課教授不存在',
        ];
    }
}
