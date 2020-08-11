<?php

namespace App\Core\Controllers;

use App\Core\Services\DIContainer;

abstract class BaseController
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Builds controller dependencies and executes
     * the given method
     */
    public static function execute($method, $data)
    {
        $controllerClass = static::class;

        $controllerWithDependencies = DIContainer::resolve($controllerClass);

        $controllerFunctionDependencies = DIContainer::resolveFunctionArgs($controllerClass, $method);
        $controllerFunctionDependencies[] = $data;
        return $controllerWithDependencies->{$method}(...$controllerFunctionDependencies);
    }
}
