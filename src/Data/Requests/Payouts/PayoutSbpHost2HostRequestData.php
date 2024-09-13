<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Traits\BankNameTrait;
use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutSbpHost2HostRequestData extends PayoutHost2HostRequestData
{
    use BankNameTrait;

    protected string $paymentMethodName = PaymentMethod::SBP_NAME;

    // Телефонный номер получателя
    private string $phoneNumber;

    // ID пользователя, выполняющего перевод
    private string $userId;

    public function __construct(
        float $payoutAmount,
        string $currencyCode,
        string $bankName,
        string $phoneNumber,
        string $userId,
        string $callbackUrl,
        string $merchantOrderId,
        string $merchantOrderDescription,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $currencyCode;
        $this->phoneNumber = $phoneNumber;
        $this->userId = $userId;
        $this->callbackUrl = $callbackUrl;
        $this->merchantOrderId = $merchantOrderId;
        $this->merchantOrderDescription = $merchantOrderDescription;

        $this->setBankName($bankName);
        $this->validateBankName();
    }

    /**
     * Получить данные для запроса
     *
     * @return array
     */
    protected function getRequestData(): array
    {
        return [
            "paymentMethodName" => $this->paymentMethodName,
            'communicationType' => CommunicationType::HOST_2_HOST,
            'payoutData' => [
                'amount' => $this->roundAmount($this->payoutAmount),
                'currency' => $this->payoutCurrency,
            ],
            'recipient' => $this->phoneNumber,
            'customerData' => [
                'bankName' => $this->bankName,
                'id' => $this->userId
            ],
            'callbackUrl' => $this->callbackUrl,
            'merchantOrderId' => $this->merchantOrderId,
            'merchantOrderDescription' => $this->merchantOrderDescription
        ];
    }
}
