<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Home page.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        $js = ('production' === config('app.env')) ? $this->elixir('js/main.js') : asset('js/main.js');

        return view('home', compact('js'));
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
}
