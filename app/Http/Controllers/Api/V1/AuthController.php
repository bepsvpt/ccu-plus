<?php

namespace App\Http\Controllers\Api\V1;

use Auth;

class AuthController extends ApiController
{
    public function signIn()
    {
    }

    public function signOut()
    {
        Auth::guard()->logout();

        return $this->responseOk();
    }

    public function signUp()
    {
    }
}
