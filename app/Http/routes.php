<?php

use Illuminate\Routing\Router;

/* @var Router $router */

$router->get('/', ['middleware' => ['web', 'secure-header'], 'as' => 'home', 'uses' => 'HomeController@home']);

$router->group(['prefix' => 'api', 'namespace' => 'Api', 'middleware' => ['web']], function (Router $router) {
    $router->group(['prefix' => 'v1', 'namespace' => 'V1'], function (Router $router) {
        $router->group(['prefix' => 'auth'], function (Router $router) {
            $router->post('sign-in', ['as' => 'signIn', 'uses' => 'AuthController@signIn']);
            $router->get('sign-out', ['as' => 'signOut', 'uses' => 'AuthController@signOut']);
            $router->post('sign-up', ['as' => 'signUp', 'uses' => 'AuthController@signUp']);
        });
    });
});
