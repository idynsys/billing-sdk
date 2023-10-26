<?php

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use Idynsys\BillingSdk\Data\PayoutRequestData;
use PHPUnit\Framework\TestCase;

class PayoutDataTest extends TestCase
{
    use CallIProtectedMethodsTrait;
    private Generator $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = Factory::create();
    }
    private function getDataObject(): PayoutRequestData
    {
        return new PayoutRequestData(
            $this->faker->uuid,
            $this->faker->shuffleString,
            $this->faker->email, $this->faker->numberBetween(1, 200),
            $this->faker->randomElement(['USD', 'EUR', 'RUB', 'KZT']),
            $this->faker->randomNumber(),
            $this->faker->realText
        );
    }

    public function testGetUrlForDepositData(): void
    {
        $dto = $this->getDataObject();

        $this->assertNotEmpty($dto->getUrl());
    }
    public function testGetRequestData():void
    {
        $method = $this->getMethod(PayoutRequestData::class, 'getRequestData');

        $dto = $this->getDataObject();

        $this->assertNotEmpty($method->invoke($dto));
    }
}