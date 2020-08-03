<?php

namespace App\Core\Middlewares;

/**
 * Base Middleware. All middlewares extend from this one
 */
class BaseMiddleware implements BaseMiddlewareInterface
{

    /**
     * BaseMiddleware constructor.
     */
    public function __construct()
    {
        $this->handle();
    }

    /**
     * The handle method for our middlewares. We must override this method in the child middlewares
     */
    public function handle()
    {
        
    }


}