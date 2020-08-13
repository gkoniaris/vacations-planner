<?php
use App\Core\Singletons\Router;

$router = Router::getInstance();

$router->get('App\Controllers\IndexController@index', '/');

$router->post('App\Controllers\AuthController@login', '/api/login');
$router->post('App\Controllers\AuthController@register', '/api/register');

$router->get('App\Controllers\IndustryController@index', '/api/taxonomies/industries');

$router->get('App\Controllers\AuthController@me', '/api/me', ['App\Middlewares\Authenticated']);
$router->get('App\Controllers\UserController@get', '/api/users', ['App\Middlewares\Authenticated', 'App\Middlewares\IsSupervisor']);
$router->post('App\Controllers\UserController@create', '/api/users', ['App\Middlewares\Authenticated', 'App\Middlewares\IsSupervisor']);
$router->PUT('App\Controllers\UserController@update', '/api/users', ['App\Middlewares\Authenticated', 'App\Middlewares\IsSupervisor']);

$router->get('App\Controllers\ApplicationController@index', '/api/applications', ['App\Middlewares\Authenticated', 'App\Middlewares\IsEmployee']);
$router->post('App\Controllers\ApplicationController@create', '/api/applications', ['App\Middlewares\Authenticated', 'App\Middlewares\IsEmployee']);
$router->post('App\Controllers\ApplicationController@process', '/api/applications/process', ['App\Middlewares\Authenticated', 'App\Middlewares\IsSupervisor']);
