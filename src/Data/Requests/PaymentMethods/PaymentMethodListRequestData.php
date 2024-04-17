<?php

namespace Idynsys\BillingSdk\Data\Requests\PaymentMethods;

use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\RequestMethod;

/**
 * @deprecated
 * Класс DTO для отправки запроса на получение списка платежных методов
 */
final class PaymentMethodListRequestData extends RequestData
{
    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_GET;

    // URL из конфигурации для выполнения запроса
    protected string $urlConfigKeyForRequest = 'PAYMENT_METHODS_URL';

}
