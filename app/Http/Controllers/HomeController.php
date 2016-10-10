<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Home page.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        return view('home');
    }

    /**
     * Application deploy.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deploy(Request $request)
    {
        list($algo, $hash) = explode('=', $request->header('X-Hub-Signature'), 2);

        if (! hash_equals($hash, hash_hmac($algo, $request->getContent(), config('services.github-webhook.secret')))) {
            \Log::notice('Github Webhook', ['auth' => 'failed', 'ip' => $request->ip()]);
        } else {
            \Log::info('Github Webhook', ['auth' => 'success', 'ip' => $request->ip()]);

            \Artisan::queue('deploy');
        }

        return response()->json('', 200);
    }

    /**
     * Reset opcache.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function opcacheReset(Request $request)
    {
        if ('127.0.0.1' === $request->ip()) {
            opcache_reset();
        }

        return response()->json('', 200);
    }
}
