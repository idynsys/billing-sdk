<?php

namespace Tests;

use Idynsys\BillingSdk\Exceptions\AnotherException;
use Idynsys\BillingSdk\Exceptions\UnauthorizedException;
use PHPUnit\Framework\TestCase;

class ExceptionsTest extends TestCase
{
    public function testUnauthorizedException(): void
    {
        $exception = new UnauthorizedException();

        $this->assertEquals(
            ['error' => 'Unauthorized', 'error_description' => 'Incorrect token'],
            $exception->getError()
        );
    }

    public function testGetOriginalError(): void
    {
        $exception = new AnotherException(['error' => 'Another error']);

        $this->assertEquals(['error' => 'Another error'], $exception->getOriginalMessage());
    }

    public function testGetErrorCode(): void
    {
        $exception = new AnotherException(['error' => 'Another error'], 499);

        $this->assertEquals(499, $exception->getErrorCode());
    }
}