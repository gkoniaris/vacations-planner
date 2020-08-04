<?php
namespace App\Core\Services;

/**
 * Service responsible for resolving class dependencies
 */
class DIContainer
{
    /**
     * Resolves class dependencies as well as the dependencies
     * of each dependency, using recursion
     */
    public static function resolve($className)
    {
        $reflector = new \ReflectionClass($className);
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
}
