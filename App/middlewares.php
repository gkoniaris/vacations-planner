<?php
use App\Core\Singletons\Request;

$generalMiddlewares = [
    'App\Middlewares\Cors'
];

$request = Request::getInstance();

foreach($generalMiddlewares as $middleware){
    new $middleware($request);
}