<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->environment(['production'])) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->setMorphMap();
    }

    /**
     * 設定 morphMap 關聯關係
     *
     * @return $this
     *
     * @link http://yish.im/2016/01/20/Laravel-morphMap-future/
     */
    protected function setMorphMap()
    {
        Relation::morphMap([
            'course' => \App\Ccu\Course::class,
            'comment' => \App\Ccu\General\Comment::class,
        ]);

        return $this;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
