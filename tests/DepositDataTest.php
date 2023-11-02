<?php

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use Idynsys\BillingSdk\Data\DepositRequestData;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class DepositDataTest extends TestCase
{

    private Generator $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = Factory::create();
    }

    private function getMethod(string $class, string $methodName): ReflectionMethod
    {
        $refDto = new ReflectionClass($class);
        $method = $refDto->getMethod($methodName);

        $method->setAccessible(true);

        return $method;
    }

    private function getDataObject(): DepositRequestData
    {
        return new DepositRequestData(
            $this->faker->uuid,
            $this->faker->shuffleString,
            $this->faker->randomNumber(),
            $this->faker->realText,
            $this->faker->email, $this->faker->numberBetween(1, 200),
            $this->faker->randomElement(['USD', 'EUR', 'RUB', 'KZT']),
            $this->faker->url()
        );
    }

    public function testGetRequestData():void
    {
        $method = $this->getMethod(DepositRequestData::class, 'getRequestData');

        $dto = $this->getDataObject();
        $data = $method->invoke($dto);

        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('payment_method_id', $data);
        $this->assertArrayHasKey('payment_method_name', $data);
        $this->assertArrayHasKey('merchant_order', $data);
        $this->assertArrayHasKey('customer_data', $data);
        $this->assertArrayHasKey('payment_data', $data);
        $this->assertArrayHasKey('callback_url', $data);
    }
}