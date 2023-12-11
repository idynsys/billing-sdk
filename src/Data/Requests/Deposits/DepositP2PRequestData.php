<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits;

use Idynsys\BillingSdk\Enums\PaymentMethod;

class DepositP2PRequestData extends DepositRequestData
{
    // ID платежного метода
    protected string $paymentMethodId = PaymentMethod::P2P_id;

    // Наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::P2P_name;

    /**
     * @param string $merchantOrderId
     * @param string $merchantOrderDescription
     * @param string $customerEmail
     * @param float $paymentAmount
     * @param string $paymentCurrencyCode
     * @param string $callbackUrl
     */
    public function __construct(
        string $merchantOrderId,
        string $merchantOrderDescription,
        string $customerEmail,
        float $paymentAmount,
        string $paymentCurrencyCode,
        string $callbackUrl
    ) {
        $this->merchantOrderId = $merchantOrderId;
        $this->merchantOrderDescription = $merchantOrderDescription;
        $this->customerEmail = $customerEmail;
        $this->paymentAmount = $paymentAmount;
        $this->paymentCurrencyCode = $paymentCurrencyCode;
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * Получить массив передаваемых данных в запрос
     *
     * @return array
     */
    protected function getRequestData(): array
    {
        return [
            'payment_method_id'   => $this->paymentMethodId,
            'payment_method_name' => $this->paymentMethodName,
            'merchant_order'      => [
                'id'          => $this->merchantOrderId,
                'description' => $this->merchantOrderDescription
            ],
            'customer_data'       => ['email' => $this->customerEmail],
            'payment_data'        => [
                'amount'   => $this->roundAmount($this->paymentAmount),
                'currency' => $this->paymentCurrencyCode
            ],
            'callback_url'        => $this->callbackUrl
        ];
    }
}