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
        if ('production' === config('app.env')) {
            $js = $this->elixir('js/main.js');
            $css = $this->elixir('css/main.css');
        } else {
            $js = asset('js/main.js');
            $css = asset('css/main.css');
        }

        return view('home', compact('js', 'css'));
    }

    /**
     * Get the path to a versioned Elixir file.
     *
     * @param  string  $file
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function elixir($file)
    {
        static $manifest = null;

        if (is_null($manifest)) {
            $manifest = json_decode(file_get_contents(public_path('assets/build/rev-manifest.json')), true);
        }

        if (isset($manifest[$file])) {
            return '/assets/build/'.$manifest[$file];
        }

        throw new \InvalidArgumentException("File {$file} not defined in asset manifest.");
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
