<?php

namespace Idynsys\BillingSdk;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Idynsys\BillingSdk\Data\RequestData;
use Idynsys\BillingSdk\Exceptions\AnotherException;
use Idynsys\BillingSdk\Exceptions\AuthException;
use Idynsys\BillingSdk\Exceptions\MethodException;
use Idynsys\BillingSdk\Exceptions\NotFoundException;
use Idynsys\BillingSdk\Exceptions\ResponseException;
use Idynsys\BillingSdk\Exceptions\UnauthorizedException;
use Idynsys\BillingSdk\Exceptions\UrlException;

/**
 * Класс для выполнения запросов к B2B backoffice
 */
class Client extends GuzzleClient
{
    // Содержимое ответа выполненного запроса
    private string $content;

    // Exception возникший при выполнении запроса
    private ?Exception $error = null;

    /**
     * Отправить запрос на B2B Backoffice
     *
     * @param RequestData $data - DTO запроса
     * @param bool $throwException - нужно ли вызывать исключительную ситуацию, если запрос выполнился с ошибкой
     * @return $this
     * @throws AnotherException
     * @throws AuthException
     * @throws MethodException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UrlException
     */
    public function sendRequestToSystem(RequestData $data, bool $throwException = true): self
    {
        $this->error = null;

        try {
            $res = $this->request($data->getMethod(), $data->getUrl(), $data->getData());

            $this->content = $res->getBody()->getContents();
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
            //$responseBody = json_decode($response->getBody()->getContents() ?: '{}', true, 512, JSON_THROW_ON_ERROR);
            //dd($response->getStatusCode(), $response->getBody()->getContents(), $response->getBody()->getContents());
            throw new ResponseException($response->getBody()->getContents(), $response->getStatusCode());
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
            $this->error = new UrlException(['error' => $exception->getHandlerContext()['error'] ?? 'url incorrect'], 503);
        }

        if ($this->error && $throwException) {
            throw $this->error;
        }

        return $this;
    }

    /**
     * Получить результат запроса. Если произошла ошибка, то вернется null
     *
     * @param string|null $key
     * @return string[]|null
     */
    public function getResult(?string $key = null): ?array
    {
        if ($this->hasError() || !isset($this->content)) {
            return null;
        }

        $data = json_decode($this->content, true, 512, JSON_THROW_ON_ERROR);

        if ($key && is_array($data)) {
            $data = [$key => array_key_exists($key, $data) ? $data[$key] : ''];
        }

        return $data;
    }

    /**
     * Проверить наличие ошибки в запросе
     *
     * @return bool
     */
    public function hasError(): bool
    {
        return !is_null($this->error);
    }

    /**
     * Получить ошибку запроса, если она произошла
     *
     * @return array|null
     */
    public function getError(): ?array
    {
        if (!$this->hasError()) {
            return null;
        }

        return $this->error->getError();
    }

}