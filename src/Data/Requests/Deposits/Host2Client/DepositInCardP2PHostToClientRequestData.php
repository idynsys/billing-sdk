<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\Deposits\DepositRequestData;
use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;

class DepositInCardP2PHostToClientRequestData extends DepositRequestData
{
    // Наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::IN_CARD_P2P;

    private string $redirectSuccessUrl;

    private string $walletUserId;

    private string $walletLogin;

    private string $walletUserFullName;

    public function __construct(
        float $paymentAmount,
        string $paymentCurrencyCode,
        string $userIpAddress,
        string $userAgent,
        string $acceptLanguage,
        string $fingerprint,
        string $walletUserId,
        string $walletLogin,
        string $walletUserFullName,
        string $callbackUrl,
        ?string $redirectSuccessUrl = null,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        string $trafficType = '',
        ?ConfigContract $config = null
    ) {
        parent::__construct($trafficType, $config);

        $this->paymentAmount = $paymentAmount;
        $this->paymentCurrencyCode = $paymentCurrencyCode;
        $this->userIpAddress = $userIpAddress;
        $this->userAgent = $userAgent;
        $this->acceptLanguage = $acceptLanguage;
        $this->fingerprint = $fingerprint;
        $this->walletUserId = $walletUserId;
        $this->walletLogin = $walletLogin;
        $this->walletUserFullName = $walletUserFullName;
        $this->merchantOrderId = $merchantOrderId;
        $this->merchantOrderDescription = $merchantOrderDescription;
        $this->callbackUrl = $callbackUrl;
        $this->redirectSuccessUrl = $redirectSuccessUrl;
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
                'payment_data' => [
                    'amount' => $this->roundAmount($this->paymentAmount),
                    'currency' => $this->paymentCurrencyCode
                ],
                'customer_data' => [
                    'ipAddress' => $this->userIpAddress,
                    'acceptLanguage' => $this->acceptLanguage,
                    'userAgent' => $this->userAgent,
                    'fingerprint' => $this->fingerprint,
                ],
                'wallet' => [
                    'userId' => $this->walletUserId,
                    'login' => $this->walletLogin,
                    'fullname' => $this->walletUserFullName,
                ],
                'merchant_order' => [
                    'id' => $this->merchantOrderId,
                    'description' => $this->merchantOrderDescription
                ],
                'callback_url' => $this->callbackUrl,
                'redirect_success_url' => $this->redirectSuccessUrl
            ] + $this->addTrafficTypeToRequestData();
    }
}
