<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class SignInRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|exists:users',
            'password' => 'required|ccu_sign_in',
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
            'username.exists' => '帳號或密碼錯誤',
        ];
    }
}
