<?php
namespace App\Core\Singletons;

use App\Core\Patterns\Singleton;
use App\Core\Singletons\Response;
use App\Core\Services\DIContainer;

use App\Middlewares\Cors;

class Request extends Singleton
{
    protected $data;
    protected $method;
    protected $router;
    protected $user;
    protected static $instance;

    /**
     * Request constructor.
     */
    protected function __construct()
    {
        $this->data = [];
        $this->query = [];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->router = Router::getInstance();
        $this->fillData();
    }

    /**
     * Returns the uri of the request
     */
    private function currentUri()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (strstr($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        $uri = '/' . trim($uri, '/');

        return $uri;
    }

    /**
     * Fills the request singleton with the data of the http request based on the method that was used
     */
    private function fillData()
    {
        try {
            if (isset($_SERVER['QUERY_STRING'])) {
                parse_str($_SERVER['QUERY_STRING'], $this->query);
                $this->query = (object) $this->query;
            }

            switch ($this->method) {
                case $this->method === 'POST' || $this->method === 'PUT':
                    $data = file_get_contents('php://input');
                    if ($data) {
                        $decodedData = json_decode($data);
    
                        if (!$decodedData) {
                            throw new \Exception('Invalid json provided');
                        }
                        
                        $this->data = $decodedData;
                    }

                    break;
            }
        } catch (\Exception $e) {
            $this->terminateRequestWithException($e);
        }
    }

    /**
     * Return a chain of route middlewares
     */
    private function buildMiddlewares($route, $request)
    {
        if (!sizeof($route['middlewares'])) return false;

        $middlewares = new $route['middlewares'][0]($request);

        $i = 1;

        while ($i < sizeof($route['middlewares']) - 1) {
            $middlewares = $middlewares->setNext(new $route['middlewares'][$i](Request::getInstance()));
            $i++;
        }

        return $middlewares;
    }

    /**
     * Returns the request http method
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * Returns the request body data as object
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Returns the request query string as object
     */
    public function query()
    {
        return $this->query;
    }
    
    /**
     * Terminates a request by returning a human readable message to the user
     * @param Exception [$e] An exception object that is passed to the function so we can get it's message
     */
    public function abort($exception, $status = 500)
    {
        if (is_string($exception)) {
            $exception = new \Exception($exception);
        }

        $response = ['message' => $exception->getMessage()];
        Response::json($response, $status);
        
        exit();
    }

    /**
     * Sets the request authenticated ser
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Returns the request authenticated user
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * Handles an incoming request by:
     * Returning to options request with cors headers
     * Handling 404 errors
     * Calling the controller to handle the request if all other checks pass
     */
    public function serve()
    {
        try {
            // Initialize request singleton
            $request = static::getInstance();

            $currentUri = $this->currentUri();
            $route = $this->router->findRouteInstance($currentUri, $this->method);

            $cors = new Cors($request);
            $cors->handle();

            /**
             * Handle 404 errors
             */
            if (!$route) {
                header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
                
                return Response::json(['error' => 'Not found']);
            }
            
            $middlewares = $this->buildMiddlewares($route, $this);
            if($middlewares) {
                $middlewareSuccessfulRun = $middlewares->handle();
                if (!$middlewareSuccessfulRun) return false;
            }
            
            $route['controller']::execute($route['method'], $this->data);
        } catch (\Exception $e) {
            $this->abort($e);
        }
    }
}
