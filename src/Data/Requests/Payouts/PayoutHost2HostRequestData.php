<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Data\Traits\TrafficTypeTrait;
use Idynsys\BillingSdk\Enums\RequestMethod;

abstract class PayoutHost2HostRequestData extends RequestData
{
    use TrafficTypeTrait;

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

    public function __construct(
        string $trafficType,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->setTrafficType($trafficType);

        $this->validateTrafficType();
    }
}
