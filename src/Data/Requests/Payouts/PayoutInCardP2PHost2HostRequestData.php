<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutInCardP2PHost2HostRequestData extends PayoutHost2HostRequestData
{
    // Параметр наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::IN_CARD_P2P;

    // ID банка
    private int $bankId;

    // Номер банковской карты
    protected string $cardNumber;

    // Месяц окончания действия карты
    protected string $cardExpiration;

    // ID пользователя электронного кошелька
    private ?string $walletUserId;

    // Login для электронного кошелька
    private string $walletLogin;

    // ФИО пользователя-владельца кошелька
    private string $walletUserFullName;

    public function __construct(
        float $payoutAmount,
        string $payoutCurrency,
        int $bankId,
        string $cardNumber,
        string $cardExpiration,
        string $walletUserId,
        string $walletLogin,
        string $walletUserFullName,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        string $trafficType = '',
        ?ConfigContract $config = null
    ) {
        parent::__construct($trafficType, $config);

        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $payoutCurrency;
        $this->bankId = $bankId;
        $this->cardNumber = $cardNumber;
        $this->cardExpiration = $cardExpiration;
        $this->walletUserId = $walletUserId;
        $this->walletLogin = $walletLogin;
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
                'communicationType' => CommunicationType::HOST_2_HOST,
                'payoutData' => [
                    'amount' => $this->roundAmount($this->payoutAmount),
                    'currency' => $this->payoutCurrency,
                ],
                'wallet' => [
                    'userId' => $this->walletUserId,
                    'login' => $this->walletLogin,
                    'fullname' => $this->walletUserFullName,
                ],
                'cardData' => [
                    'bankId' => $this->bankId,
                    'pan' => $this->cardNumber,
                    'expiration' => $this->cardExpiration
                ],
                'callbackUrl' => $this->callbackUrl,
                'merchantOrderId' => $this->merchantOrderId,
                'merchantOrderDescription' => $this->merchantOrderDescription
            ] + $this->addTrafficTypeToRequestData();
    }
}
