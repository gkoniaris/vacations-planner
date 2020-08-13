<?php
namespace App\Core\Singletons;

use App\Core\Patterns\Singleton;

class Router extends Singleton
{
    protected static $instance;
    protected $routes;
    protected $request;

    /**
     * Router constructor.
     */
    protected function __construct()
    {
        $this->routes = [];
    }

    /**
     * Creates a post route and inject into the router
     *
     * @param $controllerText
     * @param $uri
     * @param $middlewares
     */
    public function post($controllerText, $uri, $middlewares = [])
    {
        $this->initializeRouterItem($controllerText, $uri, $middlewares, 'POST');
    }

    /**
     * Creates a get route and inject into the router
     *
     * @param $controllerText
     * @param $uri
     * @param $middlewares
     */
    public function get($controllerText, $uri, $middlewares = [])
    {
        $this->initializeRouterItem($controllerText, $uri, $middlewares, 'GET');
    }

    /**
     * Creates a get route and inject into the router
     *
     * @param $controllerText
     * @param $uri
     * @param $middlewares
     */
    public function put($controllerText, $uri, $middlewares = [])
    {
        $this->initializeRouterItem($controllerText, $uri, $middlewares, 'PUT');
    }

    /**
     * Checks if a given route exists based on their uri and method
     *
     * @param $uri
     * @param $method
     *
     */
    public function findRouteInstance($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['httpMethod'] === $method) {
                return $route;
            }
        }
        
        return false;
    }

    /**
     * Returns the router instance (Singleton specific class)
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Initializes a router url, injects middlewares and adds to the rouer instance
     *
     * @param $controllerText
     * @param $uri
     * @param $middlewares
     * @param $requestMethod
     */
    private function initializeRouterItem($controllerText, $uri, &$middlewares = [], $requestMethod)
    {
        $controller = $this->getControllerName($controllerText);

        $method = $this->getControllerMethod($controllerText);

        $this->injectMiddlewares($middlewares, $requestMethod);

        $this->routes[] = ['controller' => $controller, 'method' => $method, 'httpMethod' => $requestMethod, 'uri' => $uri, 'middlewares' => $middlewares];
    }

    /**
     * Returns the name of the controller, which is the part before the @
     *
     * @param $controllerText
     */
    private function getControllerName($controllerText)
    {
        $controllerParts = explode("@", $controllerText);

        return $controllerParts[0];
    }

    /**
     * Returns the method / function of the controller, which is the part after the @
     *
     * @param $controllerText
     */
    private function getControllerMethod($controllerText)
    {
        $controllerParts = explode("@", $controllerText);

        return $controllerParts[1];
    }

    /**
     * Injects the middlewares in to the current request
     *
     * @param $middlewares
     * @param $method
     */
    private function injectMiddlewares(&$middlewares, $method)
    {
        try {
            foreach ($middlewares as $middleware) {
                $class = class_exists($middleware);
                if (!$class) {
                    throw new \Exception('Invalid middleware class');
                }
                
                $middlewareClass = is_subclass_of($middleware, 'App\Core\Middlewares\BaseMiddleware');
                if (!$middlewareClass) {
                    throw new \Exception('Middlewares must extend BaseMiddleware class');
                }
            }
        } catch (\Exception $e) {
            Request::getInstance()->abort($e);
        }
    }
}
