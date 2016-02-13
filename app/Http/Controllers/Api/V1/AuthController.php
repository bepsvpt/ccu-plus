<?php

namespace App\Http\Controllers\Api\V1;

use App\Ccu\User\User;
use App\Http\Requests\Api\V1;
use Auth;
use Cache;
use Session;

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

        $secret = [
            'username' => encrypt($request->input('username')),
            'password' => encrypt($request->input('password')),
        ];

        Session::put('ccu.sso', $secret);

        Cache::tags('user')->forever(
            md5(Auth::guard()->user()->getAuthIdentifier()),
            ['ccu' => ['sso' => $secret]]
        );

        return $this->setData($user)->responseOk();
    }

    /**
     * Sign out the application.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signOut()
    {
        $this->clearSession();

        Cache::tags('user')->forget(md5(Auth::guard()->user()->getAuthIdentifier()));

        Auth::guard()->logout();

        return $this->responseOk();
    }

    /**
     * Clear data which stored in session.
     *
     * @return void
     */
    protected function clearSession()
    {
        Session::forget([
            'ccu',
        ]);
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
