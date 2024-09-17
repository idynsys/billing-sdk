<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures\Validators;

use Idynsys\BillingSdk\Data\UniversalRequestStructures\BankCardRequestData;
use Idynsys\BillingSdk\Data\UniversalRequestStructures\CustomerRequestData;
use Idynsys\BillingSdk\Data\UniversalRequestStructures\MerchantOrderRequestData;
use Idynsys\BillingSdk\Data\UniversalRequestStructures\PaymentRequestData;
use Idynsys\BillingSdk\Data\UniversalRequestStructures\RequestDataValidationContract;
use Idynsys\BillingSdk\Data\UniversalRequestStructures\SessionDetailsRequestData;
use Idynsys\BillingSdk\Data\UniversalRequestStructures\UrlsRequestData;
use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\PaymentType;
use Idynsys\BillingSdk\Enums\TrafficType;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

abstract class ValidatorDeposit implements ValidatorContract
{
    protected string $paymentMethodName;

    protected string $communicationType;

    public function __construct(string $paymentMethodName, string $communicationType)
    {
        $this->paymentMethodName = $paymentMethodName;
        $this->communicationType = $communicationType;
    }

    public function validate(
        PaymentRequestData $paymentRequestData,
        MerchantOrderRequestData $merchantOrderRequestData,
        UrlsRequestData $urlsRequestData,
        SessionDetailsRequestData $sessionDetailsRequestData,
        CustomerRequestData $customerRequestData,
        ?BankCardRequestData $bankCardRequestData,
        string $trafficType
    ): void {
        $this->validatePaymentMethod();
        $this->validateCommunicationType();
        $this->validateTrafficType($trafficType);
        $this->validateRequestDataStructure($paymentRequestData);
        $this->validateRequestDataStructure($merchantOrderRequestData);
        $this->validateRequestDataStructure($urlsRequestData);
        $this->validateRequestDataStructure($customerRequestData);

        if ($bankCardRequestData !== null) {
            $this->validateRequestDataStructure($bankCardRequestData);
        } else {
            BankCardRequestData::checkIfShouldBe($this->paymentMethodName, $this->communicationType);
        }
    }

    private function validatePaymentMethod(): void
    {
        if (empty($this->paymentMethodName) || !in_array($this->paymentMethodName, PaymentMethod::getValues())) {
            throw new BillingSdkException(
                'Payment method name ' . $this->paymentMethodName . ' is not correct.',
                422
            );
        }
    }

    private function validateCommunicationType(): void
    {
        if (empty($this->communicationType) || !in_array($this->communicationType, CommunicationType::getValues())) {
            throw new BillingSdkException(
                'The communication type ' . $this->communicationType . ' is not correct.',
                422
            );
        }
    }

    private function validateTrafficType(?string $trafficType): void
    {
        if (empty($trafficType) || !in_array($trafficType, TrafficType::getValues())) {
            throw new BillingSdkException(
                'Traffic type ' . $this->communicationType . ' is not correct.',
                422
            );
        }
    }

    private function validateRequestDataStructure(RequestDataValidationContract $requestData): void
    {
        $requestData->validate(PaymentType::DEPOSIT, $this->communicationType, $this->paymentMethodName);
    }
}
