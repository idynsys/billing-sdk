<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Config;
use Idynsys\BillingSdk\Enums\RequestMethod;

/**
 * DTO для запроса на создание транзакции на вывод средств
 */
final class PayoutRequestData extends RequestData implements AuthenticationTokenInclude
{
    use WithAuthorizationToken;

    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_POST;

    protected string $urlConfigKeyForRequest = 'PAYOUT_URL';

    // ID платежного метода
    private string $paymentMethodId;

    // Наименование платежного метода
    private string $paymentMethodName;

    // Сумма депозита
    private float $payoutAmount;

    // Код валюты депозита
    private string $payoutCurrency;

    // Номер банковской карты
    private string $cardNumber;

    // Месяц окончания действия карты
    private string $cardExpiration;

    // Имя и Фамилия держателя карты
    private string $cardRecipientInfo;

    // URL для передачи результата создания транзакции в B2B backoffice
    private string $callbackUrl;

    public function __construct(
        string $paymentMethodId,
        string $paymentMethodName,
        float $payoutAmount,
        string $payoutCurrency,
        string $cardNumber,
        string $cardExpiration,
        string $cardRecipientInfo,
        string $callbackUrl
    ) {
        $this->paymentMethodId = $paymentMethodId;
        $this->paymentMethodName = $paymentMethodName;
        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $payoutCurrency;
        $this->cardNumber = $cardNumber;
        $this->cardExpiration = $cardExpiration;
        $this->cardRecipientInfo = $cardRecipientInfo;
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * Получить данные для запроса
     *
     * @return array
     */
    protected function getRequestData(): array
    {
        return [
            'paymentMethodId'   => $this->paymentMethodId,
            "paymentMethodName" => $this->paymentMethodName,
            'payoutData'        => [
                'amount'   => $this->payoutAmount,
                'currency' => $this->payoutCurrency
            ],
            'cardData'          => [
                'pan'           => $this->cardNumber,
                'expiration'    => $this->cardExpiration,
                'recipientInfo' => $this->cardRecipientInfo
            ],
            'callbackUrl' => $this->callbackUrl
        ];
    }
}