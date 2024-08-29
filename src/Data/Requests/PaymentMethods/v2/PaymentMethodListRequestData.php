<?php

namespace Idynsys\BillingSdk\Data\Requests\PaymentMethods\v2;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\PaymentMethods\PaymentMethodListRequestDataContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Data\Traits\PaymentTypeTrait;
use Idynsys\BillingSdk\Data\Traits\TrafficTypeTrait;
use Idynsys\BillingSdk\Enums\RequestMethod;
use Idynsys\BillingSdk\Enums\TrafficType;

final class PaymentMethodListRequestData extends RequestData implements PaymentMethodListRequestDataContract
{
    use TrafficTypeTrait;
    use PaymentTypeTrait;

    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_GET;

    // URL из конфигурации для выполнения запроса
    protected string $urlConfigKeyForRequest = 'PAYMENT_METHODS_URL';

    // Сумма депозита
    protected ?float $paymentAmount;

    // Код валюты депозита
    protected ?string $paymentCurrencyCode;

    public function __construct(
        ?float $paymentAmount = null,
        ?string $paymentCurrencyCode = null,
        ?string $paymentType = null,
        ?string $trafficType = null,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->paymentAmount = $paymentAmount;
        $this->paymentCurrencyCode = $paymentCurrencyCode;
        $this->setPaymentType($paymentType);
        $this->setTrafficType($trafficType);

        $this->validatePaymentType();

        if ($this->trafficType !== null) {
            $this->validateTrafficType();
        }
    }

    public function getRequestData(): array
    {
        $data = [];

        if ($this->paymentAmount !== null) {
            $data['amount'] = $this->roundAmount($this->paymentAmount);
        }

        if ($this->paymentCurrencyCode !== null) {
            $data['currency'] = $this->paymentCurrencyCode;
        }

        if ($this->paymentType !== null) {
            $data['paymentType'] = $this->paymentType;
        }

        if ($this->trafficType !== null) {
            $data['trafficType'] = $this->trafficType;
        }

        return $data;
    }
}
