<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits;

use Idynsys\BillingSdk\Data\Requests\Auth\AuthenticationTokenInclude;
use Idynsys\BillingSdk\Data\Requests\Auth\WithAuthorizationToken;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\RequestMethod;

/**
 * DTO для запроса на создание транзакции на пополнение счета
 */
abstract class DepositRequestData extends RequestData implements AuthenticationTokenInclude
{
    use WithAuthorizationToken;

    // ID платежного метода
    protected string $paymentMethodId;

    // Наименование платежного метода
    protected string $paymentMethodName;

    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_POST;

    protected string $urlConfigKeyForRequest = 'DEPOSIT_URL';

    // ID документа для создания депозита
    protected string $merchantOrderId;

    // описание документа для создания депозита
    protected string $merchantOrderDescription;

    // email пользователя совершающего операцию
    protected string $customerEmail;

    // Сумма депозита
    protected float $paymentAmount;

    // Код валюты депозита
    protected string $paymentCurrencyCode;

    // URL для передачи результата создания транзакции в B2B backoffice
    protected string $callbackUrl;

}