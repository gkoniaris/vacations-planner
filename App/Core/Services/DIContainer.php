<?php
namespace App\Core\Services;

use App\Core\Patterns\Singleton;

/**
 * DIContainer is a service responsible for resolving class dependencies
 */
class DIContainer
{
    /**
     * Resolves a class's dependencies in a recursive way
     *
     * @param  mixed $className The class name to resolve it's dependencies
     * 
     * @return mixed Returns an instance of the provided class along with all it's dependencies
     */
    public static function resolve($className)
    {
        $reflector = new \ReflectionClass($className);
        
        // Handle singletons injection
        if ($reflector->isSubclassOf('App\Core\Patterns\Singleton'))
        {
            return $className::getInstance();
        }
        
        $constructor = $reflector->getConstructor();

        if (!$constructor) return new $className;

        $parameters = $constructor->getParameters();
        if(!sizeof($parameters)) return new $className;

        $parametersToInject = [];

        foreach ($parameters as $parameter) {
            $internalClass = $parameter->getClass();

            if (!$internalClass) {
                $parametersToInject[] = null;
                continue;
            }

            $parametersToInject[] = self::resolve($internalClass->getName());
        }

        $concreteClass = $reflector->newInstanceArgs($parametersToInject);

        return $concreteClass;
    }

    /**
     * Resolves a methods dependencies in a recursive way
     */
    public static function resolveFunctionArgs($className, $function)
    {
        $reflector = new \ReflectionClass($className);
        $function = $reflector->getMethod($function);
        
        if (!$function) return [];

        $parameters = $function->getParameters();
        if(!sizeof($parameters)) [];

        $parametersToInject = [];

        foreach ($parameters as $parameter) {
            $internalClass = $parameter->getClass();

            if (!$internalClass) continue;

            $parametersToInject[] = self::resolve($internalClass->getName());
        }

        return $parametersToInject;
    }
}
