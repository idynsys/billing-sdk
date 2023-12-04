<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Enums\RequestMethod;

/**
 * DTO для запроса. От этого класса наследуются все DTO для запросов
 * @codeCoverageIgnore
 */
abstract class RequestData
{
    // метод запроса
    protected string $requestMethod;

    /**
     * Получить API url для выполнения запроса
     *
     * @return string
     */
    abstract public function getUrl(): string;

    /**
     * Получить данные, отправляемые в запросе
     * @return array
     */
    abstract protected function getRequestData(): array;

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
            'headers'   => $this->getHeadersData(),
            $paramsType => $this->getRequestData()
        ];
    }

    /**
     * Получить секретный ключ для приложения (Client ID)
     *
     * @return string
     */
    protected function getSecretApplicationKey(): string
    {
        dd('stop');
        return getenv('BILLING_SDK_APPLICATION_SECRET_KEY') ?: '';
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
                $this->getSecretApplicationKey()
            )
        ];
    }

}