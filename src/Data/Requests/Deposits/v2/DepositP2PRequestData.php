<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits\v2;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\Deposits\DepositRequestData;
use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;

/**
 * DTO запроса для создания депозита через платежный метод P2P
 */
class DepositP2PRequestData extends DepositRequestData
{
    // Наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::P2P_NAME;

    /**
     * @param string|null $merchantOrderId
     * @param string|null $merchantOrderDescription
     * @param string $customerEmail
     * @param float $paymentAmount
     * @param string $paymentCurrencyCode
     * @param string $callbackUrl
     */
    public function __construct(
        float $paymentAmount,
        string $paymentCurrencyCode,
        string $callbackUrl,
        string $customerEmail,
        string $userIpAddress,
        string $userAgent,
        string $acceptLanguage,
        string $fingerprint,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        string $trafficType = '',
        ?ConfigContract $config = null
    ) {
        parent::__construct($trafficType, $config);

        $this->merchantOrderId = $merchantOrderId;
        $this->merchantOrderDescription = $merchantOrderDescription;
        $this->customerEmail = $customerEmail;
        $this->paymentAmount = $paymentAmount;
        $this->paymentCurrencyCode = $paymentCurrencyCode;
        $this->callbackUrl = $callbackUrl;
        $this->userIpAddress = $userIpAddress;
        $this->userAgent = $userAgent;
        $this->acceptLanguage = $acceptLanguage;
        $this->fingerprint = $fingerprint;
    }

    /**
     * Получить массив передаваемых данных в запрос
     *
     * @return array
     */
    protected function getRequestData(): array
    {
        return [
                'payment_method_name' => $this->paymentMethodName,
                'communicationType' => CommunicationType::HOST_2_HOST,
                'merchant_order' => [
                    'id' => $this->merchantOrderId,
                    'description' => $this->merchantOrderDescription
                ],
                'customer_data' => [
                    'email' => $this->customerEmail,
                    'ipAddress' => $this->userIpAddress,
                    'acceptLanguage' => $this->acceptLanguage,
                    'userAgent' => $this->userAgent,
                    'fingerprint' => $this->fingerprint,
                ],
                'payment_data' => [
                    'amount' => $this->roundAmount($this->paymentAmount),
                    'currency' => $this->paymentCurrencyCode
                ],
                'callback_url' => $this->callbackUrl
            ] + $this->addTrafficTypeToRequestData();
    }
}
