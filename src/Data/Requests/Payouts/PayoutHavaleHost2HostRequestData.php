<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutHavaleHost2HostRequestData extends PayoutHost2HostRequestData
{
    // Параметр наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::HAVALE;

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

    private ?int $cardId;

    private string $bankIbanNo;

    private string $userBirthday;

    public function __construct(
        float $payoutAmount,
        string $payoutCurrency,
        string $walletUserId,
        string $walletLogin,
        string $walletUserFullName,
        string $callbackUrl,
        string $bankIbanNo,
        string $cardNumber,
        ?string $cardExpiration = null,
        ?int $cardId = null,
        ?int $bankId = null,
        ?string $userBirthday = null,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        string $trafficType = '',
        ?ConfigContract $config = null
    ) {
        parent::__construct($trafficType, $config);

        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $payoutCurrency;
        $this->bankId = $bankId;
        $this->cardId = $cardId;
        $this->bankIbanNo = $bankIbanNo;
        $this->userBirthday = $userBirthday;
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
            'payoutData' => [
                'amount' => $this->roundAmount($this->payoutAmount),
                'currency' => $this->payoutCurrency,
            ],
            'customerData' => [
                'birthday' => $this->userBirthday,
            ],
            'wallet' => [
                'userId' => $this->walletUserId,
                'login' => $this->walletLogin,
                'fullname' => $this->walletUserFullName,
            ],
            'cardData' => [
                'id' => $this->cardId,
                'bankId' => $this->bankId,
                'ibanNumber' => $this->bankIbanNo,
                'pan' => $this->cardNumber,
                'expiration' => $this->cardExpiration
            ],
            'callbackUrl' => $this->callbackUrl,
            'merchantOrderId' => $this->merchantOrderId,
            'merchantOrderDescription' => $this->merchantOrderDescription,
            'trafficType' => $this->trafficType,
        ];
    }
}
