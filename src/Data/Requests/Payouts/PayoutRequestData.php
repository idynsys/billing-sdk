<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Data\Requests\Auth\AuthenticationTokenInclude;
use Idynsys\BillingSdk\Data\Requests\Auth\WithAuthorizationToken;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\RequestMethod;

/**
 * DTO для запроса на создание транзакции на вывод средств
 */
abstract class PayoutRequestData extends RequestData implements AuthenticationTokenInclude
{
    use WithAuthorizationToken;

    // ID платежного метода
    protected string $paymentMethodId;

    // Наименование платежного метода
    protected string $paymentMethodName;
    
    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_POST;

    protected string $urlConfigKeyForRequest = 'PAYOUT_URL';


    // Сумма депозита
    protected float $payoutAmount;

    // Код валюты депозита
    protected string $payoutCurrency;

    // Номер банковской карты
    protected string $cardNumber;

    // Месяц окончания действия карты
    protected string $cardExpiration;

    // Имя и Фамилия держателя карты
    protected string $cardRecipientInfo;

    // URL для передачи результата создания транзакции в B2B backoffice
    protected string $callbackUrl;

    public function __construct(
        float $payoutAmount,
        string $payoutCurrency,
        string $cardNumber,
        string $cardExpiration,
        string $cardRecipientInfo,
        string $callbackUrl
    ) {
        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $payoutCurrency;
        $this->cardNumber = $cardNumber;
        $this->cardExpiration = $cardExpiration;
        $this->cardRecipientInfo = $cardRecipientInfo;
        $this->callbackUrl = $callbackUrl;
    }
}