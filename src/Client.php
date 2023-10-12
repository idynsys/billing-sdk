<?php

namespace Idynsys\BillingSdk;

use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Idynsys\BillingSdk\Data\RequestData;
use Idynsys\BillingSdk\Exceptions\AnotherException;
use Idynsys\BillingSdk\Exceptions\AuthException;
use Idynsys\BillingSdk\Exceptions\MethodException;
use Idynsys\BillingSdk\Exceptions\NotFoundException;
use Idynsys\BillingSdk\Exceptions\RequestMethodException;
use Idynsys\BillingSdk\Exceptions\UrlException;

class Client
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    private RequestData $data;
    private string $method;

    private string $content;
    private ?Exception $error = null;

    public function __construct(RequestData $data, string $method = self::METHOD_GET)
    {
        $this->data = $data;
        $this->setMethod($method);
    }

    private function setMethod(string $method): void
    {
        if (!in_array($method, [self::METHOD_GET, self::METHOD_POST])) {
            throw new RequestMethodException();
        }

        $this->method = $method;
    }

    public function send(): self
    {
        $this->error = null;

        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request($this->method, $this->data->getUrl(), $this->data->getData());

            $this->content = $res->getBody()->getContents();
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            switch ($response->getStatusCode()) {
                case 401:
                    $this->error = new AuthException($responseBody, $response->getStatusCode());
                    break;
                case 404:
                    $this->error = new NotFoundException($responseBody, $response->getStatusCode());
                    break;
                case 405:
                    $this->error = new MethodException($responseBody, $response->getStatusCode());
                    break;
                default:
                    $this->error = new AnotherException(
                        ['error' => $exception->getMessage()], $response->getStatusCode(), $exception
                    );
                    break;
            }
        } catch (ConnectException $exception) {
            $this->error = new UrlException(['error' => $exception->getHandlerContext()['error']], 503);
        }

        return $this;
    }

    public function getResult(?string $key = null): array
    {
        $data = json_decode($this->content, true);

        if ($key) {
            $data = [$key => array_key_exists($key, $data) ? $data[$key] : ''];
        }

        return $data;
    }

    public function hasError()
    {
        return !is_null($this->error);
    }

    public function getError(): ?array
    {
        if (!$this->hasError()) {
            return null;
        }

        return $this->error->getError();
    }

    public function getErrorStatus()
    {
        if (!$this->hasError()) {
            return null;
        }

        return $this->error->getErrorStatus();
    }
}