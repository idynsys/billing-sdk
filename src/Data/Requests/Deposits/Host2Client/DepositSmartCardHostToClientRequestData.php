<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\Deposits\DepositRequestData;
use Idynsys\BillingSdk\Enums\PaymentMethod;

class DepositSmartCardHostToClientRequestData extends DepositRequestData
{
    // Наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::SMART_CARD;

    private string $redirectSuccessUrl;

    private string $walletLogin;

    private string $walletUserFullName;

    public function __construct(
        float $paymentAmount,
        string $paymentCurrencyCode,
        string $walletLogin,
        string $walletUserFullName,
        string $callbackUrl,
        ?string $redirectSuccessUrl = null,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->paymentAmount = $paymentAmount;
        $this->paymentCurrencyCode = $paymentCurrencyCode;
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
            'payment_data'        => [
                'amount'   => $this->roundAmount($this->paymentAmount),
                'currency' => $this->paymentCurrencyCode
            ],
            'wallet'       => [
                'login' => $this->walletLogin,
                'fullname'    => $this->walletUserFullName,
            ],
            'merchant_order'      => [
                'id'          => $this->merchantOrderId,
                'description' => $this->merchantOrderDescription
            ],
            'callback_url'        => $this->callbackUrl,
            'redirect_success_url' => $this->redirectSuccessUrl
        ];
    }
}
