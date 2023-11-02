<?php

namespace Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Idynsys\BillingSdk\Billing;
use Idynsys\BillingSdk\Client;
use Idynsys\BillingSdk\Data\DepositRequestData;
use Idynsys\BillingSdk\Data\PaymentMethodListRequestData;
use Idynsys\BillingSdk\Data\PayoutRequestData;
use Idynsys\BillingSdk\Exceptions\UnauthorizedException;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionProperty;

class BillingTest extends TestCase
{
    private Billing $billing;

    protected function setUp(): void
    {
        parent::setUp();

        $this->billing = new Billing();
    }

    private function getClientRef(): ReflectionProperty
    {
        $billingRef = new \ReflectionClass(Billing::class);
        $clientRef = $billingRef->getProperty('client');
        $clientRef->setAccessible(true);

        return $clientRef;
    }

    private function getMockClient($response): Client
    {
        $mock = new MockHandler([$response]);
        $handlerStack = HandlerStack::create($mock);

        return new Client(['handler' => $handlerStack]);
    }

    public function testClientProperty(): void
    {
        $this->assertEquals(Client::class, get_class($this->getClientRef()->getValue($this->billing)));
    }

    public function testGetTokenRequest(): void
    {
        $responseData = ['data' => 'token-to-test'];

        $client = $this->getMockClient(new Response(200, [], json_encode($responseData)));
        $this->getClientRef()->setValue($this->billing, $client);

        $this->assertEquals($responseData['data'], $this->billing->getToken());
    }

    public function testGetTokenForRequestException(): void
    {
        $attemptProperty = new ReflectionProperty($this->billing, 'requestAttempts');
        $attemptProperty->setAccessible(true);
        $attempts = $attemptProperty->getValue($this->billing);

        $method = new ReflectionMethod($this->billing, 'getTokenForRequest');
        $method->setAccessible(true);
        $this->expectException(UnauthorizedException::class);

        $method->invoke($this->billing, $attempts);
    }

    public function testGetTokenForRequestWithRequestToken(): void
    {
        $responseData = ['data' => 'token-to-test'];

        $client = $this->getMockClient(new Response(200, [], json_encode($responseData)));
        $this->getClientRef()->setValue($this->billing, $client);
        $this->billing->getToken();

        $getTokenForRequestRef = new ReflectionMethod($this->billing, 'getTokenForRequest');
        $getTokenForRequestRef->setAccessible(true);

        $this->assertNull($getTokenForRequestRef->invoke($this->billing));
    }

    public function testGetTokenForRequestThroughRequestToken(): void
    {
        $responseData = ['data' => 'token-to-test'];

        $client = $this->getMockClient(new Response(200, [], json_encode($responseData)));
        $this->getClientRef()->setValue($this->billing, $client);

        $getTokenForRequestRef = new ReflectionMethod($this->billing, 'getTokenForRequest');
        $getTokenForRequestRef->setAccessible(true);

        $this->assertNull($getTokenForRequestRef->invoke($this->billing, 2));
        $this->assertEquals($responseData, $client->getResult());
    }

    public function testAddTokenToRequestData(): void
    {
        $data = new PaymentMethodListRequestData();

        $tokenRef = new ReflectionProperty($this->billing, 'token');
        $tokenRef->setAccessible(true);
        $tokenRef->setValue($this->billing, 'token-to-test-for-authorization-header');

        $method = new ReflectionMethod($this->billing, 'addToken');
        $method->setAccessible(true);
        $method->invoke($this->billing, $data);

        $this->assertEquals(
            'Bearer token-to-test-for-authorization-header',
            $data->getData()['headers']['Authorization']
        );
    }

    private function initClientForAuthorizedRequest(array $responseData): Client
    {
        $client = $this->getMockClient(new Response(200, [], json_encode($responseData)));
        $this->getClientRef()->setValue($this->billing, $client);

        $tokenRef = new ReflectionProperty($this->billing, 'token');
        $tokenRef->setAccessible(true);
        $tokenRef->setValue($this->billing, 'token-to-test-for-authorization-header');

        return $client;
    }

    public function testSendRequest(): void
    {
        $data = new PaymentMethodListRequestData();
        $responseData = ['items' => ['id' => 1, 'name' => 'name 1']];
        $client = $this->initClientForAuthorizedRequest($responseData);

        $method = new ReflectionMethod($this->billing, 'sendRequest');
        $method->setAccessible(true);
        $method->invoke($this->billing, $data);

        $this->assertEquals($responseData, $client->getResult());
    }

    public function testGetPaymentMethods(): void
    {
        $responseData = ['items' => ['id' => 2, 'name' => 'name 2']];
        $client = $this->initClientForAuthorizedRequest($responseData);

        $this->billing->getPaymentMethods();

        $this->assertEquals($responseData, $client->getResult());
    }

    public function testCreateDeposit(): void
    {
        $responseData = ['result' => 'success'];
        $client = $this->initClientForAuthorizedRequest($responseData);

        $data = new DepositRequestData(
            '1111',
            'P2P',
            '12321',
            'create deposit',
            'test@test.com',
            100,
            'EUR',
            'https://test.site.com/callback-for-deposit'
        );

        $this->billing->createDeposit($data);

        $this->assertEquals($responseData, $client->getResult());
    }
    public function testCreatePayout(): void
    {
        $responseData = ['result' => 'success'];
        $client = $this->initClientForAuthorizedRequest($responseData);

        $data = new PayoutRequestData(
            '1111',
            'P2P',
            100,
            'EUR',
            '1234123412341234',
            '12/30',
            'Mikhael Rich',
            'https://test.site.com/callback-for-deposit'
        );

        $this->billing->createPayout($data);

        $this->assertEquals($responseData, $client->getResult());
    }
}