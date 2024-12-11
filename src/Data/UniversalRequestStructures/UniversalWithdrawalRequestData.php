<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\PaymentType;
use Idynsys\BillingSdk\Enums\RequestMethod;
use Idynsys\BillingSdk\Validators\ValidatorFactory;

class UniversalWithdrawalRequestData extends RequestData
{
    protected string $requestMethod = RequestMethod::METHOD_POST;

    protected string $urlConfigKeyForRequest = 'UNIVERSAL_WITHDRAWAL_URL';

    protected array $requestData;

    private string $paymentMethodName;

    private string $communicationType;

    private PaymentRequestData $paymentRequestData;

    private CustomerRequestData $customerRequestData;
    private ?CustomerAccountRequestData $customerAccountData;

    private ?BankCardRequestData $bankCardRequestData;

    private UrlsRequestData $urlsRequestData;

    private MerchantOrderRequestData $merchantOrderRequestData;

    private SessionDetailsRequestData $sessionDetailsRequestData;

    public function __construct(
        string $paymentMethodName,
        string $communicationType,
        PaymentRequestData $paymentRequestData,
        MerchantOrderRequestData $merchantOrderRequestData,
        UrlsRequestData $urlsRequestData,
        SessionDetailsRequestData $sessionDetailsRequestData,
        CustomerRequestData $customerRequestData,
        ?BankCardRequestData $bankCardRequestData = null,
        ?CustomerAccountRequestData $customerAccountData = null,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->paymentMethodName = $paymentMethodName;
        $this->communicationType = $communicationType;
        $this->paymentRequestData = $paymentRequestData;
        $this->customerRequestData = $customerRequestData;
        $this->customerAccountData = $customerAccountData;
        $this->bankCardRequestData = $bankCardRequestData;
        $this->urlsRequestData = $urlsRequestData;
        $this->merchantOrderRequestData = $merchantOrderRequestData;
        $this->sessionDetailsRequestData = $sessionDetailsRequestData;

        $this->validate();
    }

    private function validate(): void
    {
        $validator = ValidatorFactory::make(
            PaymentType::WITHDRAWAL,
            $this->paymentMethodName,
            $this->communicationType
        );

        $validator->validate(
            $this->paymentRequestData,
            $this->merchantOrderRequestData,
            $this->urlsRequestData,
            $this->sessionDetailsRequestData,
            $this->customerRequestData,
            $this->bankCardRequestData,
            null,
            $this->customerAccountData,
        );
    }


    protected function getRequestData(): array
    {
        if (!isset($this->requestData)) {
            $this->requestData = [
                'paymentMethodName' => $this->paymentMethodName,
                'communicationType' => $this->communicationType,
                'payment' => $this->paymentRequestData->getRequestData(),
                'merchantOrder' => $this->merchantOrderRequestData->getRequestData(),
                'sessionDetails' => $this->sessionDetailsRequestData->getRequestData(),
            ];

            $customerData = $this->customerRequestData->getRequestData(
                PaymentType::WITHDRAWAL,
                $this->communicationType,
                $this->paymentMethodName
            );

            if ($customerData) {
                $this->requestData['customer'] = $customerData;
            }

            if ($this->bankCardRequestData !== null) {
                $bankCardData = $this->bankCardRequestData->getRequestData(
                    PaymentType::WITHDRAWAL,
                    $this->communicationType,
                    $this->paymentMethodName
                );

                if ($bankCardData) {
                    $this->requestData['card'] = $bankCardData;
                }
            }

            $urlsData = $this->urlsRequestData->getRequestData(
                PaymentType::WITHDRAWAL,
                $this->communicationType,
                $this->paymentMethodName
            );

            if ($urlsData) {
                $this->requestData['urls'] = $urlsData;
            }

            if ($this->customerAccountData !== null) {
                $customerAccountData = $this->customerAccountData->getRequestData(
                    PaymentType::DEPOSIT,
                    $this->communicationType,
                    $this->paymentMethodName
                );

                if ($customerAccountData) {
                    $this->requestData['customerAccount'] = $customerAccountData;
                    $bankName = $customerData['bankName'] ?? null;
                    if ($bankName !== null) {
                        $this->requestData['customerAccount']['bankName'] = $bankName;
                    }
                }
            }
        }

        return $this->requestData;
    }
}
