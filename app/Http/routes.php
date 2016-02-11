<?php

use Illuminate\Routing\Router;

/* @var Router $router */

$router->group(['middleware' => 'secure-header'], function (Router $router) {
    $router->get('/', ['middleware' => ['web'], 'as' => 'home', 'uses' => 'HomeController@home']);

    $router->post('deploy', ['uses' => 'HomeController@deploy']);
    $router->get('opcache-reset', ['as' => 'opcache-reset', 'uses' => 'HomeController@opcacheReset']);
});

$router->group(['prefix' => 'api', 'namespace' => 'Api', 'middleware' => ['web']], function (Router $router) {
    $router->group(['prefix' => 'v1', 'namespace' => 'V1'], function (Router $router) {
        $router->group(['prefix' => 'auth'], function (Router $router) {
            $router->post('sign-in', ['middleware' => ['throttle:3,1'], 'uses' => 'AuthController@signIn']);
            $router->get('sign-out', ['middleware' => ['auth'], 'uses' => 'AuthController@signOut']);
            $router->post('sign-up', ['middleware' => ['throttle:3,1'], 'uses' => 'AuthController@signUp']);
        });

        $router->get('profile', 'UserController@profile');

        $router->group(['prefix' => 'courses'], function (Router $router) {
            $router->get('search', 'CourseController@search');
            $router->get('waterfall', 'CourseCommentControl@waterfall');
            $router->get('{courses}', 'CourseController@show');
            $router->get('{courses}/comments', 'CourseCommentControl@index');
            $router->post('{courses}/comments', 'CourseCommentControl@store');
            $router->patch('{courses}/comments/{comments}/like', 'CourseCommentControl@like');
        });

        $router->group(['prefix' => 'ecourse-lite', 'middleware' => ['auth']], function (Router $router) {
            $router->get('course-list', 'EcourseLiteController@courseList');
            $router->get('course-content/{courseId}', 'EcourseLiteController@courseContent');
        });

        $router->group(['prefix' => 'resources'], function (Router $router) {
            $router->get('colleges/{name?}', 'ResourceController@colleges');
        });
    });
});
