<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\RequestMethod;
use Idynsys\BillingSdk\Enums\TrafficType;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

abstract class PayoutHost2HostRequestData extends RequestData
{
    // Наименование платежного метода
    protected string $paymentMethodName = 'n/a';

    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_POST;

    // URL из конфигурации для выполнения запроса
    protected string $urlConfigKeyForRequest = 'PAYOUT_URL';

    // Сумма депозита
    protected float $payoutAmount;

    // Код валюты депозита
    protected string $payoutCurrency;

    // URL для передачи результата создания транзакции в B2B backoffice
    protected string $callbackUrl;

    // ID документа для создания депозита
    protected ?string $merchantOrderId;

    // описание документа для создания депозита
    protected ?string $merchantOrderDescription;

    protected string $trafficType;

    public function __construct(
        string $trafficType,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->trafficType = $trafficType;
        $this->validateTrafficType();
    }

    protected function validateTrafficType()
    {
        if (
            $this->trafficType !== '' &&
            $this->trafficType !== TrafficType::FDT &&
            $this->trafficType !== TrafficType::TRUSTED
        ) {
            throw new BillingSdkException('TrafficType must be empty string (""), "fdt" or "trusted".', 422);
        }
    }
}
