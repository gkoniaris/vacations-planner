<?php

namespace App\Core\Middlewares;

/**
 * Base Middleware. All middlewares extend from this one
 */
abstract class BaseMiddleware implements BaseMiddlewareInterface
{

    protected $nextMiddleware = null;

    public function __construct()
    {

    }
    
    /**
     * Sets the next middleware to be called
     */
    public function setNext(BaseMiddleware $nextMiddleware)
    {
        $this->nextMiddleware = $nextMiddleware;

        return $nextMiddleware;
    }

    /**
     * Executes the middleware logic
     */
    public function handle()
    {
        return false;
    }

    /**
     * Calls the next middleware if one is set
     */
    public function next(...$params)
    {
        if (sizeof($params)) throw new \Exception($params[0]);

        if($this->nextMiddleware) $this->nextMiddleware->handle();
    }
}
