<?php

namespace Tests;

use Idynsys\BillingSdk\Data\Requests\PaymentMethods\PaymentMethodListRequestData;
use PHPUnit\Framework\TestCase;

class PaymentMethodsDataTest extends TestCase
{
    use CallIProtectedMethodsTrait;

    public function testGetUrlForAuthData(): void
    {
        $dto = new PaymentMethodListRequestData();

        $this->assertNotEmpty($dto->getUrl());
    }
    public function testGetRequestData():void
    {
        $method = $this->getMethod(PaymentMethodListRequestData::class, 'getRequestData');

        $dto = new PaymentMethodListRequestData();

        $this->assertEmpty($method->invoke($dto));
    }
}