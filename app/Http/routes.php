<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['middleware' => ['web']], function (Router $router) {
    $router->get('/', ['uses' => 'HomeController@home']);

    $router->group(['domain' => 'ecourse.ccu.plus'], function (Router $router) {
        $router->get('/', ['uses' => 'HomeController@home']);
    });

    $router->group(['domain' => 'sso.ccu.plus', 'namespace' => 'Sso', 'as' => 'sso.'], function (Router $router) {
        $router->post('sign-in', ['as' => 'signIn', 'uses' => 'AuthController@signIn']);
        $router->get('sign-out', ['as' => 'signOut', 'uses' => 'AuthController@signOut']);
    });
});
