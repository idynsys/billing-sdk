<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\RequestMethod;

abstract class PayoutHost2HostRequestData extends RequestData
{
    // Наименование платежного метода
    protected string $paymentMethodName = 'n/a';

    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_POST;

    // URL из конфигурации для выполнения запроса
    protected string $urlConfigKeyForRequest = 'PAYOUT_URL';

    public function __construct(
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);
    }
}
