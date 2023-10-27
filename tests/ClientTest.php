<?php

namespace Tests;

use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Idynsys\BillingSdk\Client;
use Idynsys\BillingSdk\Data\AuthRequestData;
use Idynsys\BillingSdk\Data\PaymentMethodListRequestData;
use Idynsys\BillingSdk\Exceptions\AnotherException;
use Idynsys\BillingSdk\Exceptions\AuthException;
use Idynsys\BillingSdk\Exceptions\MethodException;
use Idynsys\BillingSdk\Exceptions\NotFoundException;
use Idynsys\BillingSdk\Exceptions\UnauthorizedException;
use Idynsys\BillingSdk\Exceptions\UrlException;
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
        $client->sendRequestToSystem(new AuthRequestData());
    }

    private function mockResponse($response): Client
    {
        $mock = new MockHandler([$response]);
        $handlerStack = HandlerStack::create($mock);

        return new Client(['handler' => $handlerStack]);
    }

    public function testResponse(): void
    {
        $responseData = ['token' => 'token-string'];

        $client = $this->mockResponse(new Response(200, [], json_encode($responseData)));

        $client->sendRequestToSystem(new AuthRequestData());

        $this->assertEquals($responseData, $client->getResult());
    }

    public function testResponseWithoutException(): void
    {
        $responseData = ['token' => 'token-string'];

        $client = $this->mockResponse(
            new ClientException(
                'Authorization Error',
                new Request('GET', 'test'),
                new Response(401, [], json_encode($responseData))
            )
        );

        $client->sendRequestToSystem(new AuthRequestData(), false);

        $this->assertEquals($responseData, $client->getError());
    }

    public function testNotAuthorized(): void
    {
        $this->expectException(AuthException::class);

        $client = $this->mockResponse(
            new ClientException(
                'Authorization Error',
                new Request('GET', 'test'),
                new Response(401, [], json_encode(['error' => 'not Authorized']))
            )
        );

        $data = new PaymentMethodListRequestData();

        $data->setToken('some-token');

        $client->sendRequestToSystem($data);
    }

    public function testNotAuthorizedWithoutBody(): void
    {
        $this->expectException(UnauthorizedException::class);

        $client = $this->mockResponse(new ClientException('Not auth', new Request('GET', 'test'), new Response(401)));

        $data = new PaymentMethodListRequestData();

        $data->setToken('some-token');

        $client->sendRequestToSystem($data);
    }

    public function testNotFound(): void
    {
        $this->expectException(NotFoundException::class);

        $client = $this->mockResponse(new ClientException('Not found', new Request('GET', 'test'), new Response(404)));
        $data = new PaymentMethodListRequestData();

        $data->setToken('some-token');

        $client->sendRequestToSystem($data);
    }

    public function testIncorrectMethod(): void
    {
        $this->expectException(MethodException::class);

        $client = $this->mockResponse(new ClientException('Method', new Request('GET', 'test'), new Response(405)));
        $data = new PaymentMethodListRequestData();

        $data->setToken('some-token');

        $client->sendRequestToSystem($data);
    }

    public function testClientExceptionAnotherError(): void
    {
        $this->expectException(AnotherException::class);

        $client = $this->mockResponse(new ClientException('Method', new Request('GET', 'test'), new Response(499)));
        $data = new PaymentMethodListRequestData();

        $data->setToken('some-token');

        $client->sendRequestToSystem($data);
    }

    public function testConnectException(): void
    {
        $this->expectException(UrlException::class);

        $client = $this->mockResponse(new ConnectException('Method', new Request('GET', 'test')));
        $data = new PaymentMethodListRequestData();

        $data->setToken('some-token');

        $client->sendRequestToSystem($data);
    }
}