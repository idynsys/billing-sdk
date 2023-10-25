<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Config\Config;
use Idynsys\BillingSdk\Enums\RequestMethod;

/**
 * DTO для запроса на создание транзакции на вывод средств
 */
final class PayoutRequestData extends RequestData implements AuthorisationTokenInclude
{
    use WithAuthorizationToken;

    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_POST;

    // ID платежного метода
    private string $paymentMethodId;

    // Наименование платежного метода
    private string $paymentMethodName;

    // Сумма депозита
    private string $payoutAmount;

    // Код валюты депозита
    private string $payoutCurrency;

    // Номер банковской карты
    private string $cardNumber;

    // Месяц окончания действия карты
    private string $cardExpiration;

    // Имя и Фамилия держателя карты
    private string $cardRecipientInfo;

    public function __construct(
        $paymentMethodId,
        $paymentMethodName,
        $payoutAmount,
        $payoutCurrency,
        $cardNumber,
        $cardExpiration,
        $cardRecipientInfo
    ) {
        $this->paymentMethodId = $paymentMethodId;
        $this->paymentMethodName = $paymentMethodName;
        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $payoutCurrency;
        $this->cardNumber = $cardNumber;
        $this->cardExpiration = $cardExpiration;
        $this->cardRecipientInfo = $cardRecipientInfo;
    }

    /**
     * Получить API url для создания транзакции на вывод средств
     *
     * @return string
     */
    public function getUrl(): string
    {
        return getenv('BILLING_SDK_MODE') === 'PRODUCTION'
            ? Config::PROD_PAYOUT_URL : Config::PREPROD_PAYOUT_URL;
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
            ]

        ];
    }
}