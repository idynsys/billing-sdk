<?php

namespace Idynsys\BillingSdk\Data\Requests;

use Idynsys\BillingSdk\Data\AuthenticationTokenInclude;
use Idynsys\BillingSdk\Data\RequestData;
use Idynsys\BillingSdk\Data\WithAuthorizationToken;
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