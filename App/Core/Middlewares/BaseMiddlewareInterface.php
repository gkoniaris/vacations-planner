<?php

namespace App\Core\Middlewares;

/**
 * Base Middleware Interface
 */
interface BaseMiddlewareInterface
{
    public function setNext(BaseMiddleware $nextMiddleware);
    
    public function handle();
    
    public function next(...$params);
}
