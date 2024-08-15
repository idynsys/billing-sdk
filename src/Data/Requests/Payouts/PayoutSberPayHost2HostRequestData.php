<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutSberPayHost2HostRequestData extends PayoutHost2HostRequestData
{
    // Параметр наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::SBER_PAY_NAME;

    private string $cardNumber;

    private string $userIpAddress;

    private string $userAgent;

    private string $userAcceptLanguage;

    private string $fingerprint;

    public function __construct(
        float $payoutAmount,
        string $currencyCode,
        string $cardNumber,
        string $userIpAddress,
        string $userAgent,
        string $userAcceptLanguage,
        string $fingerprint,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        ?ConfigContract $config = null
    ) {
        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $currencyCode;
        $this->cardNumber = $cardNumber;
        $this->userIpAddress = $userIpAddress;
        $this->userAgent = $userAgent;
        $this->userAcceptLanguage = $userAcceptLanguage;
        $this->fingerprint = $fingerprint;
        $this->callbackUrl = $callbackUrl;
        $this->merchantOrderId = $merchantOrderId;
        $this->merchantOrderDescription = $merchantOrderDescription;

        parent::__construct($config);
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
            'payoutData'        => [
                'amount'   => $this->roundAmount($this->payoutAmount),
                'currency' => $this->payoutCurrency,
            ],
            'card'          => [
                'pan'           => $this->cardNumber,
            ],
            'customerData' => [
                'ipAddress' => $this->userIpAddress,
                'userAgent' => $this->userAgent,
                'acceptLanguage' => $this->userAcceptLanguage,
                'fingerprint' => $this->fingerprint,
            ],
            'callbackUrl' => $this->callbackUrl,
            'merchantOrderId' => $this->merchantOrderId,
            'merchantOrderDescription' => $this->merchantOrderDescription
        ];
    }
}
