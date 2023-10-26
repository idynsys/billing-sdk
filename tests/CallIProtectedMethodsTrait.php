<?php

namespace Tests;

use ReflectionClass;
use ReflectionMethod;

trait CallIProtectedMethodsTrait
{
    private function getMethod(string $class, string $methodName): ReflectionMethod
    {
        $refDto = new ReflectionClass($class);
        $method = $refDto->getMethod($methodName);

        $method->setAccessible(true);

        return $method;
    }
}