<?php

namespace App\Core\Middlewares;

use App\Core\Singletons\Response;

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
     *
     * @param  mixed $nextMiddleware A middleware having BaseMiddlewareInterface as it's parent class
     * @return mixed
     */
    public function setNext(BaseMiddlewareInterface $nextMiddleware)
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
     *
     * @param  mixed $params
     * @return void
     */
    public function next(...$params)
    {
        if (sizeof($params)) return Response::json(['error' => $params[0]]);

        if($this->nextMiddleware) {
            return $this->nextMiddleware->handle();
        }

        return true;
    }
}
