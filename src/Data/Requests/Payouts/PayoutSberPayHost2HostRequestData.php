<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutSberPayHost2HostRequestData extends PayoutHost2HostRequestData
{
    // Параметр наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::SBER_PAY_NAME;

    // Номер банковской карты пользователя
    private string $cardNumber;

    // IP адрес пользователя
    private string $userIpAddress;

    // User-Agent пользователя
    private string $userAgent;

    // ID пользователя
    private string $userId;

    public function __construct(
        float $payoutAmount,
        string $currencyCode,
        string $cardNumber,
        string $userId,
        string $userIpAddress,
        string $userAgent,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        string $trafficType = '',
        ?ConfigContract $config = null
    ) {
        parent::__construct($trafficType, $config);

        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $currencyCode;
        $this->cardNumber = $cardNumber;
        $this->userId = $userId;
        $this->userIpAddress = $userIpAddress;
        $this->userAgent = $userAgent;
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
            'payoutData' => [
                'amount' => $this->roundAmount($this->payoutAmount),
                'currency' => $this->payoutCurrency,
            ],
            'cardData' => [
                'pan' => $this->cardNumber,
            ],
            'customerData' => [
                'id' => $this->userId,
                'ipAddress' => $this->userIpAddress,
                'userAgent' => $this->userAgent
            ],
            'callbackUrl' => $this->callbackUrl,
            'merchantOrderId' => $this->merchantOrderId,
            'merchantOrderDescription' => $this->merchantOrderDescription
        ] + $this->addTrafficTypeToRequestData();
    }
}
