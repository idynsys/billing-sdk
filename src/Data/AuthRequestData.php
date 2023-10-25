<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Config\Config;
use Idynsys\BillingSdk\Enums\RequestMethod;

/**
 * Класс DTO для запроса на получение токена аутентификации
 */
final class AuthRequestData extends RequestData
{
    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_POST;

    /**
     * Получить ID клиента
     *
     * @return string
     */
    private function getClientId(): string
    {
        return getenv('BILLING_SDK_CLIENT_ID') ?: '';
    }

    /**
     * Получить url API для запроса
     * @return string
     */
    public function getUrl(): string
    {
        return getenv('BILLING_SDK_MODE') === 'PRODUCTION' ? Config::PROD_AUTH_URL : Config::PREPROD_AUTH_URL;
    }

    /**
     * Получить данные для запроса
     *
     * @return array{clientId: string}
     */
    protected function getRequestData(): array
    {
        return [
            'clientId' => $this->getClientId(),
        ];
    }
}