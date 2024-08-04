<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutPepHost2HostRequestData extends PayoutHost2HostRequestData
{
    // Параметр наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::PEP;

    // ID банка
    private int $bankId;

    // ID карты пользователя
    private int $cardId;

    // ID пользователя электронного кошелька
    private ?string $walletUserId;

    // ФИО пользователя-владельца кошелька
    private string $walletUserFullName;

    private string $walletAccountNumber;

    public function __construct(
        float $payoutAmount,
        string $payoutCurrency,
        int $bankId,
        int $cardId,
        string $walletAccountNumber,
        string $walletUserId,
        string $walletUserFullName,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $payoutCurrency;
        $this->bankId = $bankId;
        $this->cardId = $cardId;
        $this->walletAccountNumber = $walletAccountNumber;
        $this->walletUserId = $walletUserId;
        $this->walletUserFullName = $walletUserFullName;
        $this->callbackUrl = $callbackUrl;
        $this->merchantOrderId = $merchantOrderId;
        $this->merchantOrderDescription = $merchantOrderDescription;
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
            'payoutData' => [
                'amount' => $this->roundAmount($this->payoutAmount),
                'currency' => $this->payoutCurrency,
            ],
            'card' => [
                'id' => $this->cardId,
                'bankId' => $this->bankId
            ],
            'wallet' => [
                'pan' => $this->walletAccountNumber,
                'userId' => $this->walletUserId,
                'fullname' => $this->walletUserFullName,
            ],
            'callbackUrl' => $this->callbackUrl,
            'merchantOrderId' => $this->merchantOrderId,
            'merchantOrderDescription' => $this->merchantOrderDescription
        ];
    }
}
