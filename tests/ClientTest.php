<?php

namespace Tests;

use Exception;
use GuzzleHttp\Exception\ServerException;
use Idynsys\BillingSdk\Client;
use Idynsys\BillingSdk\Data\AuthRequestData;
use Idynsys\BillingSdk\Data\PaymentMethodListRequestData;
use Idynsys\BillingSdk\Exceptions\AnotherException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ClientTest extends TestCase
{
    public function testHasErrorAsNull(): void
    {
        $client = new Client();

        $this->assertFalse($client->hasError());
    }

    private function setClientError($client, Exception $exception): void
    {
        $clientRef = new ReflectionClass(Client::class);

        $property = $clientRef->getProperty('error');
        $property->setAccessible(true);
        $property->setValue($client, $exception);
    }

    public function testHasErrorAsException(): void
    {
        $client = new Client();
        $this->setClientError($client, new Exception('test exception'));

        $this->assertTrue($client->hasError());
    }

    public function testGetErrorAsException(): void
    {
        $client = new Client();
        $exception = new AnotherException(['message' => 'test exception']);

        $this->setClientError($client, $exception);

        $this->assertEquals(['message' => 'test exception'], $client->getError());
    }

    public function testGetErrorAsNull(): void
    {
        $client = new Client();

        $this->assertNull($client->getError());
    }

    public function testGetResultAsNull(): void
    {
        $client = new Client();

        $this->assertNull($client->getResult());
    }

    public function testGetResultAsArray(): void
    {
        $client = new Client();

        $clientRef = new ReflectionClass(Client::class);

        $property = $clientRef->getProperty('content');
        $property->setAccessible(true);
        $property->setValue($client, json_encode(['status' => 'SUCCESS']));

        $this->assertEquals(['status' => 'SUCCESS'], $client->getResult());
        $this->assertEquals(['status' => 'SUCCESS'], $client->getResult('status'));
        $this->assertEquals(['status1' => ''], $client->getResult('status1'));
    }

    public function testSendRequest(): void
    {
        $client = new Client();

        $this->expectException(ServerException::class);
        $client->send(new AuthRequestData());
    }
}