<?php

namespace App\Http\Controllers\Api\V1;

use App\Ccu\User\User;
use App\Http\Requests\Api\V1;
use Auth;
use Cache;
use Hash;
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

        $this->setUpCache($request->only('username', 'password'));

        return $this->setData($user)->responseOk();
    }

    /**
     * Set up cache.
     *
     * @param array $secret
     * @return void
     */
    protected function setUpCache($secret)
    {
        $secret['username'] = encrypt($secret['username']);
        $secret['password'] = encrypt($secret['password']);

        Session::put('ccu.sso', $secret);

        Cache::tags('user')->forever(
            md5(Auth::guard()->user()->getAuthIdentifier()),
            ['ccu' => ['sso' => $secret]]
        );
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
        if ($request->has(['old_email', 'old_password'])) {
            if (! $this->transformOldAccount($request)) {
                return $this->setMessages(['username' => ['舊信箱不存在或舊密碼錯誤']])->responseUnprocessableEntity();
            }

            $user = User::where('username', $request->input('username'))->first();
        } else {
            $user = User::create($request->only(['username', 'nickname']));
        }

        if (! $user->exists) {
            return $this->responseUnknownError();
        }

        $user->fresh();

        Auth::guard()->login($user, true);

        $this->setUpCache($request->only('username', 'password'));

        return $this->setData($user)->responseCreated();
    }

    /**
     * 轉移舊帳號.
     *
     * @param V1\SignUpRequest $request
     * @return bool
     */
    protected function transformOldAccount($request)
    {
        $user = User::where('username', $request->input('old_email'))->first();

        if (is_null($user)) {
            return false;
        } elseif (! Hash::check($request->input('old_password'), $user->getAttribute('remember_token'))) {
            return false;
        }

        $user->update([
            'username' => $request->input('username'),
            'nickname' => $request->input('nickname'),
        ]);

        return true;
    }
}
