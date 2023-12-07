<?php

namespace Idynsys\BillingSdk\Data;

class DepositP2PRequestData extends DepositRequestData
{
    /**
     * @param string $paymentMethodId
     * @param string $paymentMethodName
     * @param string $merchantOrderId
     * @param string $merchantOrderDescription
     * @param string $customerEmail
     * @param float $paymentAmount
     * @param string $paymentCurrencyCode
     * @param string $callbackUrl
     */
    public function __construct(
        string $paymentMethodId,
        string $paymentMethodName,
        string $merchantOrderId,
        string $merchantOrderDescription,
        string $customerEmail,
        float $paymentAmount,
        string $paymentCurrencyCode,
        string $callbackUrl
    ) {
        $this->paymentMethodId = $paymentMethodId;
        $this->paymentMethodName = $paymentMethodName;
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
            'merchant_order'      => ['id' => $this->merchantOrderId, 'description' => $this->merchantOrderDescription],
            'customer_data'       => ['email' => $this->customerEmail],
            'payment_data'        => ['amount' => $this->paymentAmount, 'currency' => $this->paymentCurrencyCode],
            'callback_url'        => $this->callbackUrl
        ];
    }
}