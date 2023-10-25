<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Config\Config;

use Idynsys\BillingSdk\Enums\RequestMethod;

/**
 * Класс DTO для отправки запроса на получение списка платежных методов
 */
final class PaymentMethodListRequestData extends RequestData implements AuthorisationTokenInclude
{
    use WithAuthorizationToken;

    protected string $requestMethod = RequestMethod::METHOD_GET;

    /**
     * Получить url API для отправки запроса
     *
     * @return string
     */
    public function getUrl(): string
    {
        return getenv(
            'BILLING_SDK_MODE'
        ) === 'PRODUCTION' ? Config::PROD_PAYMENT_METHODS_URL : Config::PREPROD_PAYMENT_METHODS_URL;
    }

    /**
     * Получить данные передаваемые в запрос
     *
     * @return array
     */
    protected function getRequestData(): array
    {
        return [];
    }
}