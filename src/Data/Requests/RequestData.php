<?php

namespace Idynsys\BillingSdk\Data\Requests;

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

    // URL из конфигурации для выполнения запрос, заполняется в конкретном классе-наследнике
    protected string $urlConfigKeyForRequest;

    /**
     * Получить полный URL для выполнения запроса с учетом режима работы приложения
     *
     * @return string
     */
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
            'headers'   => $this->getHeadersData(),
            $paramsType => $this->getRequestData()
        ];
    }

    /**
     * Получение подписи по параметрам запроса
     *
     * @return string
     */
    protected function getSignature(): string
    {
        $dataForSign = $this->getRequestData();

        if ($this->getMethod() === RequestMethod::METHOD_GET) {
            array_walk_recursive($dataForSign, function (&$item) {
                if (is_numeric($item)) {
                    $item = (string) $item;
                }
            });
        }

        return hash_hmac(
            'sha512',
            json_encode($dataForSign),
            Config::get('clientSecret')
        );
    }

    /**
     * Получить данные заголовка
     *
     * @return array{X-Authorization-Sign: string}
     */
    protected function getHeadersData(): array
    {
        return [
            'X-Client-Id'          => Config::get('clientId'),
            'X-Authorization-Sign' => $this->getSignature(),
        ];
    }

    /**
     * Преобразование float в строку с двумя знаками после запятой
     *
     * @param float $number
     * @return string
     */
    protected function roundAmount(float $number): string
    {
        return number_format($number, 2, '.', '');
    }
}