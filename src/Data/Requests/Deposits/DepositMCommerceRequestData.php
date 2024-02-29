<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits;

use Idynsys\BillingSdk\Enums\PaymentMethod;

/**
 * @deprecated
 * Использовать ./v2/DepositMCommerceRequestData
 *
 * DTO запроса для создания депозита через платежный метод MCommerce
 */
class DepositMCommerceRequestData extends DepositRequestData
{
    // Параметр наименования платежного метода
    protected string $paymentMethodName = PaymentMethod::M_COMMERCE_NAME;

    // Параметр телефонный номер
    public string $phoneNumber;

    /**
     * @param string|null $merchantOrderId
     * @param string|null $merchantOrderDescription
     * @param string $phoneNumber
     * @param float $paymentAmount
     * @param string $paymentCurrencyCode
     * @param string $callbackUrl
     */
    public function __construct(
        ?string $merchantOrderId,
        ?string $merchantOrderDescription,
        string $phoneNumber,
        float $paymentAmount,
        string $paymentCurrencyCode,
        string $callbackUrl
    ) {
        $this->merchantOrderId = $merchantOrderId;
        $this->merchantOrderDescription = $merchantOrderDescription;
        $this->phoneNumber = $phoneNumber;
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
            'customer_data'       => [
                'phoneNumber' => $this->phoneNumber,
            ],
            'payment_data'        => [
                'amount'   => $this->roundAmount($this->paymentAmount),
                'currency' => $this->paymentCurrencyCode
            ],
            'callback_url'        => $this->callbackUrl
        ];
    }
}