<?php

namespace Idynsys\BillingSdk\Validators;

use Idynsys\BillingSdk\Data\UniversalRequestStructures\BankCardRequestData;
use Idynsys\BillingSdk\Data\UniversalRequestStructures\CustomerRequestData;
use Idynsys\BillingSdk\Data\UniversalRequestStructures\MerchantOrderRequestData;
use Idynsys\BillingSdk\Data\UniversalRequestStructures\PaymentRequestData;
use Idynsys\BillingSdk\Data\UniversalRequestStructures\SessionDetailsRequestData;
use Idynsys\BillingSdk\Data\UniversalRequestStructures\UrlsRequestData;

interface ValidatorContract
{
    public function validate(
        PaymentRequestData $paymentRequestData,
        MerchantOrderRequestData $merchantOrderRequestData,
        UrlsRequestData $urlsRequestData,
        SessionDetailsRequestData $sessionDetailsRequestData,
        CustomerRequestData $customerRequestData,
        ?BankCardRequestData $bankCardRequestData,
        ?string $trafficType = null
    ): void;
}
