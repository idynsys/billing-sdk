<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutM10Host2HostRequestData extends PayoutHost2HostRequestData
{
    // Параметр наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::M10;

    // Сумма депозита
    protected float $payoutAmount;

    // ID пользователя электронного кошелька
    private ?string $walletUserId;

    // Login для электронного кошелька
    private string $walletLogin;

    // ФИО пользователя-владельца кошелька
    private string $walletUserFullName;

    // Номер счета кошелька
    private string $walletAccountNumber;

    // URL для передачи результата создания транзакции в B2B backoffice
    protected string $callbackUrl;

    // ID документа для создания депозита
    protected ?string $merchantOrderId;

    // описание документа для создания депозита
    protected ?string $merchantOrderDescription;

    private string $phoneNumber;

    public function __construct(
        float $payoutAmount,
        string $phoneNumber,
        string $walletUserId,
        string $walletLogin,
        string $walletUserFullName,
        string $walletAccountNumber,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->payoutAmount = $payoutAmount;
        $this->phoneNumber = $phoneNumber;
        $this->walletUserId = $walletUserId;
        $this->walletLogin = $walletLogin;
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
            'payoutData'        => [
                'amount'   => $this->roundAmount($this->payoutAmount),
            ],
            'wallet'       => [
                'userId'   => $this->walletUserId,
                'login' => $this->walletLogin,
                'fullName'    => $this->walletUserFullName,
                'pan'    => $this->walletAccountNumber,
            ],
            'recipient'     => $this->phoneNumber,
            'callbackUrl' => $this->callbackUrl,
            'merchantOrderId' => $this->merchantOrderId,
            'merchantOrderDescription' => $this->merchantOrderDescription
        ];
    }
}
