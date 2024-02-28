<?php

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutBankcardRequestData;
use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutRequestData;
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
        return new PayoutBankcardRequestData(
            $this->faker->numberBetween(1, 200),
            $this->faker->randomElement(['USD', 'EUR', 'RUB', 'KZT']),
            $this->faker->creditCardNumber(),
            $this->faker->creditCardExpirationDateString(),
            $this->faker->name(),
            $this->faker->url()
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
        $data = $method->invoke($dto);

        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('paymentMethodName', $data);
        $this->assertArrayHasKey('payoutData', $data);
        $this->assertArrayHasKey('amount', $data['payoutData']);
        $this->assertArrayHasKey('currency', $data['payoutData']);
        $this->assertArrayHasKey('cardData', $data);
        $this->assertArrayHasKey('pan', $data['cardData']);
        $this->assertArrayHasKey('expiration', $data['cardData']);
        $this->assertArrayHasKey('recipientInfo', $data['cardData']);
        $this->assertArrayHasKey('callbackUrl', $data);
    }
}