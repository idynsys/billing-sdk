<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\TrafficType;

class PayoutPaparaHost2HostRequestData extends PayoutHost2HostRequestData
{
    // Параметр наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::PAPARA;

    // ID пользователя электронного кошелька
    private ?string $walletUserId;

    // ФИО пользователя-владельца кошелька
    private string $walletUserFullName;

    // Номер счета кошелька
    private string $walletAccountNumber;

    public function __construct(
        float $payoutAmount,
        string $payoutCurrency,
        string $walletUserId,
        string $walletUserFullName,
        string $walletAccountNumber,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        string $trafficType = TrafficType::FDT,
        ?ConfigContract $config = null
    ) {
        parent::__construct($trafficType, $config);

        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $payoutCurrency;
        $this->walletUserId = $walletUserId;
        $this->walletUserFullName = $walletUserFullName;
        $this->walletAccountNumber = $walletAccountNumber;
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
                'communicationType' => CommunicationType::HOST_2_HOST,
                'payoutData' => [
                    'amount' => $this->roundAmount($this->payoutAmount),
                    'currency' => $this->payoutCurrency,
                ],
                'wallet' => [
                    'userId' => $this->walletUserId,
                    'fullname' => $this->walletUserFullName,
                    'pan' => $this->walletAccountNumber,
                ],
                'callbackUrl' => $this->callbackUrl,
                'merchantOrderId' => $this->merchantOrderId,
                'merchantOrderDescription' => $this->merchantOrderDescription
            ] + $this->addTrafficTypeToRequestData();
    }
}
