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
    protected float $paymentAmount;

    // Код валюты депозита
    protected string $paymentCurrencyCode;

    public function __construct(
        ?float $paymentAmount = null,
        ?string $paymentCurrencyCode = null,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->paymentAmount = $paymentAmount;
        $this->paymentCurrencyCode = $paymentCurrencyCode;
    }

    public function getRequestData(): array
    {
        return [
            'amount'   => $this->roundAmount($this->paymentAmount),
            'currency' => $this->paymentCurrencyCode
        ];
    }
}
