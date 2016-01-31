<?php

namespace Test\Application;

use Config;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_see_local_js_path_if_environment_is_not_production()
    {
        $this->visit(route('home'))->see('/js/main.js');
    }

    /**
     * @test
     */
    public function it_should_see_production_js_path_if_environment_is_production()
    {
        Config::set('app.env', 'production');

        $this->visit(route('home'))->see('/assets/build/js/main-');

        Config::set('app.env', 'testing');
    }
}
