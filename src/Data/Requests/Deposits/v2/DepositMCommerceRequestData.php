<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits\v2;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\Deposits\DepositRequestData;
use Idynsys\BillingSdk\Enums\PaymentMethod;

/**
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
        float $paymentAmount,
        string $paymentCurrencyCode,
        string $phoneNumber,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

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
