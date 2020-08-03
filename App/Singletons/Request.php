<?php
namespace App\Singletons;

use App\Patterns\Singleton;
use App\Singletons\Response;
use App\Middlewares\Cors;

class Request extends Singleton
{
    private $data;
    protected $method;
    protected $router;
    protected $user;
    protected static $instance;

    /**
     * Request constructor.
     */
    protected function __construct(){
        $this->data = [];
        $this->query = [];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->router = Router::getInstance();
        $this->fillData();
    }

    /**
     * Returns the request instance (Singleton specific class)
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Returns the request http method
     */
    public static function method()
    {
        return static::getInstance()->method;
    }

    /**
     * Returns the request body data as object
     */
    public static function data()
    {
        return static::getInstance()->data;
    }

    /**
     * Returns the request query string as object
     */
    public static function query()
    {
        return static::getInstance()->query;
    }
    

    /**
     * Terminates a request by returning a human readable message to the user
     * @param Exception [$e] An exception object that is passed to the function so we can get it's message
     */
    public function terminateRequestWithException($e, $status = 500)
    {
        $response = ['message' => $e->getMessage()];
        Response::json($response, $status);
        
        exit();
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
            $currentUri = $this->currentUri();
            $route = $this->router->findRouteInstance($currentUri, $this->method());

            /**
             * Handle 404 errors
             */
            if(!$route) {
                $cors = new Cors(Request::getInstance());

                // Don't return 404 for options requests
                if ($this->method === 'OPTIONS') {
                    exit(0);    
                }

                header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
                
                return Response::json(['error' => 'Not found']);
            }

            $reflector = new \ReflectionClass($route['controller']);
            $parameters = $reflector->getConstructor()->getParameters();

            $parametersToInject = [];

            foreach($parameters as $parameter)
            {
                $classObject = $parameter->getClass();

                if ($classObject->isInstantiable()) {
                    $className = $classObject->name;
                    $class = new $className();
                    $parametersToInject[] = $class;
                }
            }
            
            $controller = new $route['controller'](...$parametersToInject);

            foreach($route['middlewares'] as $middleware) {
                new $middleware(Request::getInstance());
            }

            $controller->{$route['method']}();
        } catch(\Exception $e) {
            $this->terminateRequestWithException($e);
        }
    }

    /**
     * Returns the uri of the request
     */
    private function currentUri()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
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

            switch($this->method) {
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

        } catch(\Exception $e) {
            $this->terminateRequestWithException($e);
        }
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
}