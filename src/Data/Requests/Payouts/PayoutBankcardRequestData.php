<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Traits\BankNameTrait;
use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;

/**
 * DTO запроса для создания вывода средств через платежный метод Bankcard
 */
class PayoutBankcardRequestData extends PayoutRequestData
{
    use BankNameTrait;

    // Параметр наименования платежного метода
    protected string $paymentMethodName = PaymentMethod::BANKCARD_NAME;

    public function __construct(
        float $payoutAmount,
        string $payoutCurrency,
        string $cardNumber,
        string $cardExpiration,
        string $cardRecipientInfo,
        string $bankName,
        string $userId,
        string $callbackUrl,
        string $merchantOrderId,
        string $merchantOrderDescription,
        ?ConfigContract $config = null
    ) {
        parent::__construct(
            $payoutAmount,
            $payoutCurrency,
            $cardNumber,
            $cardExpiration,
            $cardRecipientInfo,
            $userId,
            $callbackUrl,
            $merchantOrderId,
            $merchantOrderDescription,
            $config
        );

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
                'currency' => $this->payoutCurrency
            ],
            'cardData' => [
                'pan' => $this->cardNumber,
                'expiration' => $this->cardExpiration,
                'recipientInfo' => $this->cardRecipientInfo
            ],
            'customerData' => [
                'bankName' => $this->bankName,
                'id' => $this->userId
            ],
            'callbackUrl' => $this->callbackUrl,
            'merchantOrderId' => $this->merchantOrderId,
            'merchantOrderDescription' => $this->merchantOrderDescription,
        ];
    }
}
