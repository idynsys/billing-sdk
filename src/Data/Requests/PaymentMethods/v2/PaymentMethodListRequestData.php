<?php

namespace Idynsys\BillingSdk\Data\Requests\PaymentMethods\v2;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\PaymentMethods\PaymentMethodListRequestDataContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\RequestMethod;

final class PaymentMethodListRequestData extends RequestData implements PaymentMethodListRequestDataContract
{
    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_GET;

    // URL из конфигурации для выполнения запроса
    protected string $urlConfigKeyForRequest = 'PAYMENT_METHODS_URL';

    // Сумма депозита
    protected ?float $paymentAmount;

    // Код валюты депозита
    protected ?string $paymentCurrencyCode;

    // Тип платежа
    protected ?string $paymentType;

    public function __construct(
        ?float $paymentAmount = null,
        ?string $paymentCurrencyCode = null,
        ?string $paymentType = null,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->paymentAmount = $paymentAmount;
        $this->paymentCurrencyCode = $paymentCurrencyCode;
        $this->paymentType = $paymentType;
    }

    public function getRequestData(): array
    {
        return [
            'amount'   => $this->paymentAmount ? $this->roundAmount($this->paymentAmount) : null,
            'currency' => $this->paymentCurrencyCode,
            'paymentType' => $this->paymentType,
        ];
    }
}
