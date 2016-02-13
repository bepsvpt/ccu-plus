<?php

namespace App\Http\Middleware;

use Auth;
use Cache;
use Closure;
use Session;

class SignInViaRemember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard()->check() && Auth::guard()->viaRemember()) {
            $data = Cache::tags('user')->get(md5(Auth::guard()->user()->getAuthIdentifier()));

            Session::put('ccu.sso', $data['ccu']['sso']);
        }

        return $next($request);
    }
}
