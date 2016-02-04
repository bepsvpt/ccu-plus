<?php

namespace App\Http\Controllers\Api\V1;

use App\Ccu\User\User;
use App\Http\Requests\Api\V1;
use Auth;

class AuthController extends ApiController
{
    /**
     * Sign in the application.
     *
     * @param V1\SignInRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn(V1\SignInRequest $request)
    {
        $user = User::where('username', $request->input('username'))->first();

        Auth::guard()->login($user, true);

        return $this->setData($user)->responseOk();
    }

    /**
     * Sign out the application.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signOut()
    {
        Auth::guard()->logout();

        return $this->responseOk();
    }

    /**
     * Sign up the application.
     *
     * @param V1\SignUpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(V1\SignUpRequest $request)
    {
        $user = User::create($request->only(['username', 'nickname']));

        if (! $user->exists) {
            return $this->responseUnknownError();
        }

        $user->fresh();

        Auth::guard()->login($user, true);

        return $this->setData($user)->responseCreated();
    }
}
