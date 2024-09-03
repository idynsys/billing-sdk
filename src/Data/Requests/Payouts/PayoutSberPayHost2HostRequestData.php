<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Enums\CommunicationType;
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

    private string $recipientInfo;

    private string $phoneNumber;

    public function __construct(
        float $payoutAmount,
        string $currencyCode,
        string $cardNumber,
        string $cardRecipientInfo,
        string $phoneNumber,
        string $userId,
        string $userIpAddress,
        string $userAgent,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $currencyCode;
        $this->cardNumber = $cardNumber;
        $this->recipientInfo = $cardRecipientInfo;
        $this->phoneNumber = $phoneNumber;
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
            'communicationType' => CommunicationType::HOST_2_HOST,
            'payoutData' => [
                'amount' => $this->roundAmount($this->payoutAmount),
                'currency' => $this->payoutCurrency,
            ],
            'cardData' => [
                'pan' => $this->cardNumber,
                'recipientInfo' => $this->recipientInfo,
            ],
            'recipient' => $this->phoneNumber,
            'customerData' => [
                'id' => $this->userId,
                'ipAddress' => $this->userIpAddress,
                'userAgent' => $this->userAgent
            ],
            'callbackUrl' => $this->callbackUrl,
            'merchantOrderId' => $this->merchantOrderId,
            'merchantOrderDescription' => $this->merchantOrderDescription
        ];
    }
}
