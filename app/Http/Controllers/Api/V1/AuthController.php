<?php

namespace App\Http\Controllers\Api\V1;

use App\Ccu\User\User;
use App\Http\Requests\Api\V1;
use Auth;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    /**
     * Sign in the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn(Request $request)
    {
        if (! Auth::guard()->attempt($request->only(['username', 'password']), true)) {
            return $this->setMessages(['Invalid username or password.'])->responseUnprocessableEntity();
        }

        return $this->setData(Auth::guard()->user())->responseOk();
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
     * @param V1\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(V1\RegisterRequest $request)
    {
        $user = User::create($request->only(['username', 'nickname', 'email']));

        if (! $user->exists) {
            return $this->responseUnknownError();
        }

        $user->fresh();

        Auth::guard()->login($user, true);

        return $this->setData($user)->responseCreated();
    }
}
