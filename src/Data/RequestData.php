<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Config;
use Idynsys\BillingSdk\Enums\RequestMethod;

/**
 * DTO для запроса. От этого класса наследуются все DTO для запросов
 * @codeCoverageIgnore
 */
abstract class RequestData
{
    // метод запроса
    protected string $requestMethod;

    protected string $urlConfigKeyForRequest;

    protected function getRequestUrlConfigKey(): string
    {
        return Config::get(Config::get('mode', 'DEVELOPMENT') === 'PRODUCTION' ? 'prod_host' : 'preprod_host')
            . Config::get($this->urlConfigKeyForRequest);
    }

    /**
     * Получить API url для выполнения запроса
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->getRequestUrlConfigKey();
    }

    /**
     * Получить данные, отправляемые в запросе
     * @return array
     */
    protected function getRequestData(): array
    {
        return [];
    }

    /**
     * Подучить метод запроса
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->requestMethod ?: RequestMethod::METHOD_GET;
    }

    /**
     * Получить данные и заголовки передаваемые в запрос
     *
     * @return array
     */
    public function getData(): array
    {
        $paramsType = $this->getMethod() === RequestMethod::METHOD_POST ? 'json' : 'query';

        return [
            'headers' => $this->getHeadersData(),
            $paramsType => $this->getRequestData()
        ];
    }

    /**
     * Получить данные заголовка
     *
     * @return array{X-Authorization-Sign: string}
     */
    protected function getHeadersData(): array
    {
        return [
            'X-Authorization-Sign' => hash_hmac(
                'sha512',
                json_encode($this->getRequestData()),
                Config::get('clientSecret')
            )
        ];
    }

}