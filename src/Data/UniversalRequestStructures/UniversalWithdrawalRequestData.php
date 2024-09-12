<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;

class UniversalWithdrawalRequestData extends RequestData
{
    private string $paymentMethodName;

    private string $communicationType;

    private PaymentRequestData $paymentRequestData;

    private CustomerRequestData $customerRequestData;

    private BankCardRequestData $bankCardRequestData;

    private UrlsRequestData $urlsRequestData;

    private MerchantOrderRequestData $merchantOrderRequestData;

    private SessionDetailsRequestData $sessionDetailsRequestData;

    public function __construct(
        string $paymentMethodName,
        string $communicationType,
        PaymentRequestData $paymentRequestData,
        CustomerRequestData $customerRequestData,
        BankCardRequestData $bankCardRequestData,
        UrlsRequestData $urlsRequestData,
        MerchantOrderRequestData $merchantOrderRequestData,
        SessionDetailsRequestData $sessionDetailsRequestData,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->paymentMethodName = $paymentMethodName;
        $this->communicationType = $communicationType;
        $this->paymentRequestData = $paymentRequestData;
        $this->customerRequestData = $customerRequestData;
        $this->bankCardRequestData = $bankCardRequestData;
        $this->urlsRequestData = $urlsRequestData;
        $this->merchantOrderRequestData = $merchantOrderRequestData;
        $this->sessionDetailsRequestData = $sessionDetailsRequestData;
    }
}
