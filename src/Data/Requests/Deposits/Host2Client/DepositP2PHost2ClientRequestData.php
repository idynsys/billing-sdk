<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\Deposits\DepositRequestData;
use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\TrafficType;

/**
 * @deprecated
 * Не поддерживается с выходом версии 5.0. Нужно пользоваться универсальным методом создания транзакции
 *
 * DTO запроса для создания депозита через платежный метод P2P Host2Client
 */
class DepositP2PHost2ClientRequestData extends DepositRequestData
{
    // Наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::P2P_NAME;
    protected string $customerId;

    /**
     * @param string|null $merchantOrderId
     * @param string|null $merchantOrderDescription
     * @param string $customerId
     * @param float $paymentAmount
     * @param string $paymentCurrencyCode
     * @param string $callbackUrl
     */
    public function __construct(
        float $paymentAmount,
        string $paymentCurrencyCode,
        string $customerId,
        string $userIpAddress,
        string $userAgent,
        string $acceptLanguage,
        string $fingerprint,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        string $trafficType = TrafficType::FTD,
        ?ConfigContract $config = null
    ) {
        parent::__construct($trafficType, $config);

        $this->merchantOrderId = $merchantOrderId;
        $this->merchantOrderDescription = $merchantOrderDescription;
        $this->paymentAmount = $paymentAmount;
        $this->paymentCurrencyCode = $paymentCurrencyCode;
        $this->callbackUrl = $callbackUrl;
        $this->customerId = $customerId;
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
                'communicationType' => CommunicationType::HOST_2_CLIENT,
                'merchant_order' => [
                    'id' => $this->merchantOrderId,
                    'description' => $this->merchantOrderDescription
                ],
                'customer_data' => [
                    'email' => 'admin@test.com',
                    'id' => $this->customerId,
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
