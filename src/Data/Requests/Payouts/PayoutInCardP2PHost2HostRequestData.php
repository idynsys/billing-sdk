<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutInCardP2PHost2HostRequestData extends PayoutHost2HostRequestData
{
    // Параметр наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::IN_CARD_P2P;

    // Сумма депозита
    protected float $payoutAmount;

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

    // ID банка для кошелька
    private int $walletBankId;

    // URL для передачи результата создания транзакции в B2B backoffice
    protected string $callbackUrl;

    // ID документа для создания депозита
    protected ?string $merchantOrderId;

    // описание документа для создания депозита
    protected ?string $merchantOrderDescription;
    private string $phoneNumber;

    public function __construct(
        float $payoutAmount,
        string $cardNumber,
        string $cardExpiration,
        string $phoneNumber,
        string $walletUserId,
        string $walletLogin,
        string $walletUserFullName,
        int $walletBankId,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->payoutAmount = $payoutAmount;
        $this->cardNumber = $cardNumber;
        $this->cardExpiration = $cardExpiration;
        $this->phoneNumber = $phoneNumber;
        $this->walletUserId = $walletUserId;
        $this->walletLogin = $walletLogin;
        $this->walletUserFullName = $walletUserFullName;
        $this->walletBankId = $walletBankId;
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
            'payoutData'        => [
                'amount'   => $this->roundAmount($this->payoutAmount),
            ],
            'wallet'       => [
                'userId'   => $this->walletUserId,
                'login' => $this->walletLogin,
                'fullName'    => $this->walletUserFullName,
                'bankId'    => $this->walletBankId,
            ],
            'recipient'     => $this->phoneNumber,
            'card'          => [
                'pan'           => $this->cardNumber,
                'expiration'    => $this->cardExpiration
            ],
            'callbackUrl' => $this->callbackUrl,
            'merchantOrderId' => $this->merchantOrderId,
            'merchantOrderDescription' => $this->merchantOrderDescription
        ];
    }
}
