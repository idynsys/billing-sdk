<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits;

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
        ?string $merchantOrderId,
        ?string $merchantOrderDescription,
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