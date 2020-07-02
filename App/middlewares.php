<?php
use App\Singletons\Request;

$generalMiddlewares = [
    'App\Middlewares\Cors'
];

$request = Request::getInstance();

foreach($generalMiddlewares as $middleware){
    new $middleware($request);
}