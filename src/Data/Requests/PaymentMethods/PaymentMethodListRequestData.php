<?php

namespace Idynsys\BillingSdk\Data\Requests\PaymentMethods;

use Idynsys\BillingSdk\Data\Requests\Auth\AuthenticationTokenInclude;
use Idynsys\BillingSdk\Data\Requests\Auth\WithAuthorizationToken;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\RequestMethod;

/**
 * Класс DTO для отправки запроса на получение списка платежных методов
 */
final class PaymentMethodListRequestData extends RequestData implements AuthenticationTokenInclude
{
    use WithAuthorizationToken;

    protected string $requestMethod = RequestMethod::METHOD_GET;

    protected string $urlConfigKeyForRequest = 'PAYMENT_METHODS_URL';

}