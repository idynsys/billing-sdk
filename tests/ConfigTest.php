<?php

namespace Tests;

use Idynsys\BillingSdk\Config\Config;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ConfigTest extends TestCase
{
    private function getConfig(): ReflectionClass
    {
        return new ReflectionClass(Config::class);
    }

    public function testProdAuthUrlExists(): void
    {
        $this->assertArrayHasKey('PROD_AUTH_URL', $this->getConfig()->getConstants());
    }

    public function testPreprodAuthUrlExists(): void
    {
        $this->assertArrayHasKey('PREPROD_AUTH_URL', $this->getConfig()->getConstants());
    }

    public function testProdPaymentMethodsUrlExists(): void
    {
        $this->assertArrayHasKey('PROD_PAYMENT_METHODS_URL', $this->getConfig()->getConstants());
    }

    public function testPreprodPaymentMethodsUrlExists(): void
    {
        $this->assertArrayHasKey('PREPROD_PAYMENT_METHODS_URL', $this->getConfig()->getConstants());
    }

    public function testProdCreateDepositUrlExists(): void
    {
        $this->assertArrayHasKey('PROD_DEPOSIT_URL', $this->getConfig()->getConstants());
    }

    public function testPreprodCreateDepositUrlExists(): void
    {
        $this->assertArrayHasKey('PREPROD_DEPOSIT_URL', $this->getConfig()->getConstants());
    }

    public function testProdCreatePayoutUrlExists(): void
    {
        $this->assertArrayHasKey('PROD_PAYOUT_URL', $this->getConfig()->getConstants());
    }

    public function testPreprodCreatePayoutUrlExists(): void
    {
        $this->assertArrayHasKey('PREPROD_PAYOUT_URL', $this->getConfig()->getConstants());
    }

    public function testProdDepositCallBackUrlExists(): void
    {
        $this->assertArrayHasKey('PROD_DEPOSIT_CALLBACK', $this->getConfig()->getConstants());
    }

    public function testPreprodDepositCallBackUrlExists(): void
    {
        $this->assertArrayHasKey('PREPROD_DEPOSIT_CALLBACK', $this->getConfig()->getConstants());
    }

    public function testConfig()
    {
        $this->assertIsObject(new Config());
    }
}