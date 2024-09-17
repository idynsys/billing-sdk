<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Data\UniversalRequestStructures\Validators\ValidatorFactory;
use Idynsys\BillingSdk\Enums\PaymentType;
use Idynsys\BillingSdk\Enums\RequestMethod;

class UniversalDepositRequestData extends RequestData
{
    protected string $requestMethod = RequestMethod::METHOD_POST;

    private string $paymentMethodName;

    private string $communicationType;

    private string $trafficType;

    private PaymentRequestData $paymentRequestData;

    private CustomerRequestData $customerRequestData;

    private ?BankCardRequestData $bankCardRequestData;

    private UrlsRequestData $urlsRequestData;

    private MerchantOrderRequestData $merchantOrderRequestData;

    private SessionDetailsRequestData $sessionDetailsRequestData;

    public function __construct(
        string $paymentMethodName,
        string $communicationType,
        string $trafficType,
        PaymentRequestData $paymentRequestData,
        MerchantOrderRequestData $merchantOrderRequestData,
        UrlsRequestData $urlsRequestData,
        SessionDetailsRequestData $sessionDetailsRequestData,
        CustomerRequestData $customerRequestData,
        ?BankCardRequestData $bankCardRequestData = null,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->paymentMethodName = $paymentMethodName;
        $this->communicationType = $communicationType;
        $this->trafficType = $trafficType;
        $this->paymentRequestData = $paymentRequestData;
        $this->customerRequestData = $customerRequestData;
        $this->bankCardRequestData = $bankCardRequestData;
        $this->urlsRequestData = $urlsRequestData;
        $this->merchantOrderRequestData = $merchantOrderRequestData;
        $this->sessionDetailsRequestData = $sessionDetailsRequestData;

        $this->validate();
    }

    private function validate(): void
    {
        $validator = ValidatorFactory::make(PaymentType::DEPOSIT, $this->paymentMethodName, $this->communicationType);;

        $validator->validate(
            $this->paymentRequestData,
            $this->merchantOrderRequestData,
            $this->urlsRequestData,
            $this->sessionDetailsRequestData,
            $this->customerRequestData,
            $this->bankCardRequestData,
            $this->trafficType
        );
    }

    protected function getRequestData(): array
    {
        $dataToRequest = [
            'paymentMethodName' => $this->paymentMethodName,
            'communicationType' => $this->communicationType,
            'trafficType' => $this->trafficType,
            'payment' => $this->paymentRequestData->getRequestData(),
            'merchantOrder' => $this->merchantOrderRequestData->getRequestData(),
            'sessionDetails' => $this->sessionDetailsRequestData->getRequestData(),
            'customer' => $this->customerRequestData->getRequestData(
                PaymentType::DEPOSIT,
                $this->communicationType,
                $this->paymentMethodName),
            'urls' => $this->urlsRequestData->getRequestData(
                PaymentType::DEPOSIT,
                $this->communicationType,
                $this->paymentMethodName)
        ];

        if ($this->bankCardRequestData !== null) {
            $bankCardData = $this->bankCardRequestData->getRequestData(
                PaymentType::DEPOSIT,
                $this->communicationType,
                $this->paymentMethodName
            );

            if ($bankCardData) {
                $dataToRequest['card'] = $bankCardData;
            }
        }

        return $dataToRequest;
    }
}
