<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Config;
use Idynsys\BillingSdk\Enums\RequestMethod;

/**
 * DTO для запроса на создание транзакции на пополнение счета
 */
abstract class DepositRequestData extends RequestData implements AuthenticationTokenInclude
{
    use WithAuthorizationToken;

    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_POST;

    protected string $urlConfigKeyForRequest = 'DEPOSIT_URL';

    // ID платежного метода
    private string $paymentMethodId;

    // Наименование платежного метода
    private string $paymentMethodName;

    // ID документа для создания депозита
    private string $merchantOrderId;

    // описание документа для создания депозита
    private string $merchantOrderDescription;

    // email пользователя совершающего операцию
    private string $customerEmail;

    // Сумма депозита
    private float $paymentAmount;

    // Код валюты депозита
    private string $paymentCurrencyCode;

    // URL для передачи результата создания транзакции в B2B backoffice
    private string $callbackUrl;

}