<?php
namespace App\Core\Singletons;

use App\Core\Patterns\Singleton;

class Response extends Singleton{

    protected static $instance;

    /**
     * Returns the response instance (Singleton specific class)
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Returns a json response along with headers and response code
     */
    public static function json($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        
        echo json_encode($data);
    }

    /**
     * Redirects to another url
     */
    public static function redirect($url)
    {
        header('Location: ' . $url);

        exit();
    }
}