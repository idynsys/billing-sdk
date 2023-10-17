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
use Idynsys\BillingSdk\Exceptions\UnauthorizedException;
use Idynsys\BillingSdk\Exceptions\UrlException;

class Client
{
    private string $content;
    private ?Exception $error = null;

    public function __construct()
    {
        //
    }

    public function send(RequestData $data, bool $throwException = true): self
    {
        $this->error = null;

        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request($data->getMethod(), $data->getUrl(), $data->getData());

            $this->content = $res->getBody()->getContents();
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            switch ($response->getStatusCode()) {
                case 401:
                    if ($responseBody) {
                        $this->error = new AuthException($responseBody, $response->getStatusCode());
                    } else {
                        $this->error = new UnauthorizedException($response->getStatusCode());
                    }
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

        if ($this->error && $throwException) {
            throw $this->error;
        }

        return $this;
    }

    public function getResult(?string $key = null): ?array
    {
        if ($this->hasError()) {
            return null;
        }

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