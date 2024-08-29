<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\TrafficType;

class PayoutSmartCardHost2HostRequestData extends PayoutHost2HostRequestData
{
    // Параметр наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::SMART_CARD;

    // Номер банковской карты
    protected string $cardNumber;

    // Месяц окончания действия карты
    protected string $cardExpiration;

    // Login для электронного кошелька
    private string $walletLogin;

    // ФИО пользователя-владельца кошелька
    private string $walletUserFullName;

    public function __construct(
        float $payoutAmount,
        string $currencyCode,
        string $cardNumber,
        string $cardExpiration,
        string $walletLogin,
        string $walletUserFullName,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        string $trafficType = TrafficType::FDT,
        ?ConfigContract $config = null
    ) {
        parent::__construct($trafficType, $config);

        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $currencyCode;
        $this->cardNumber = $cardNumber;
        $this->cardExpiration = $cardExpiration;
        $this->walletLogin = $walletLogin;
        $this->walletUserFullName = $walletUserFullName;
        $this->callbackUrl = $callbackUrl;
        $this->merchantOrderId = $merchantOrderId;
        $this->merchantOrderDescription = $merchantOrderDescription;
    }

    /**
     * Получить данные для запроса
     *
     * @return array
     */
    protected function getRequestData(): array
    {
        return [
                "paymentMethodName" => $this->paymentMethodName,
                'communicationType' => CommunicationType::HOST_2_HOST,
                'payoutData' => [
                    'amount' => $this->roundAmount($this->payoutAmount),
                    'currency' => $this->payoutCurrency,
                ],
                'wallet' => [
                    'login' => $this->walletLogin,
                    'fullname' => $this->walletUserFullName,
                ],
                'cardData' => [
                    'pan' => $this->cardNumber,
                    'expiration' => $this->cardExpiration
                ],
                'callbackUrl' => $this->callbackUrl,
                'merchantOrderId' => $this->merchantOrderId,
                'merchantOrderDescription' => $this->merchantOrderDescription
            ] + $this->addTrafficTypeToRequestData();
    }
}
