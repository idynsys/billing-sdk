<?php

namespace Tests;

use Idynsys\BillingSdk\Data\Requests\Auth\AuthRequestData;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class AuthDataTest extends TestCase
{
    private function getMethod(string $class, string $methodName): ReflectionMethod
    {
        $refDto = new ReflectionClass($class);
        $method = $refDto->getMethod($methodName);

        $method->setAccessible(true);

        return $method;
    }

    public function testGetUrlForAuthData(): void
    {
        $dto = new AuthRequestData();

        $this->assertNotEmpty($dto->getUrl());
    }

    public function testGetClientIdForAuthData(): void
    {
        $method = $this->getMethod(AuthRequestData::class, 'getClientId');

        $dto = new AuthRequestData();

        $this->assertNotEmpty($method->invoke($dto));

    }

    public function testGetRequestData():void
    {
        $method = $this->getMethod(AuthRequestData::class, 'getRequestData');

        $dto = new AuthRequestData();

        $this->assertNotEmpty($method->invoke($dto));
    }
}