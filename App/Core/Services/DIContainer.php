<?php
namespace App\Core\Services;

class DIContainer
{
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
