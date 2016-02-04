<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class SignUpRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'  => 'required|integer|digits_between:9,9|unique:users',
            'password'  => 'required|ccu_sign_in',
            'nickname'  => 'required|min:3|max:12|unique:users',
            'g-recaptcha-response' => 'required|recaptcha',
        ];
    }
}
