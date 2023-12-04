<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Config\Config;
use Idynsys\BillingSdk\Enums\RequestMethod;

/**
 * DTO для запроса на создание транзакции на пополнение счета
 */
final class DepositRequestData extends RequestData implements AuthenticationTokenInclude
{
    use WithAuthorizationToken;

    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_POST;

    // ID платежного метода
    private string $paymentMethodId;

    // Наименование платежного метода
    private string $paymentMethodName;

    // ID документа для создания депозита
    private string $merchantOrderId;

    // описание документа для создания депозита
    private string $merchantOrderDescription;

    // email пользователя совершающего операцию
    private string $customerEmail;

    // Сумма депозита
    private float $paymentAmount;

    // Код валюты депозита
    private string $paymentCurrencyCode;

    // URL для передачи результата создания транзакции в B2B backoffice
    private string $callbackUrl;

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
     * Получить url API для выполнения запроса для создания депозита
     *
     * @return string
     */
    public function getUrl(): string
    {
        return getenv('BILLING_SDK_MODE') === 'PRODUCTION'
            ? Config::PROD_DEPOSIT_URL : Config::PREPROD_DEPOSIT_URL;
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