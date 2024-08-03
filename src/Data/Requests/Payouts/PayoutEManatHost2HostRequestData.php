<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutEManatHost2HostRequestData extends PayoutHost2HostRequestData
{
    // Параметр наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::E_MANAT;

    // Сумма депозита
    protected float $payoutAmount;

    // Login для электронного кошелька
    private string $walletLogin;

    // ФИО пользователя-владельца кошелька
    private string $walletUserFullName;

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
        string $walletLogin,
        string $walletUserFullName,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->payoutAmount = $payoutAmount;
        $this->phoneNumber = $phoneNumber;
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
            'payoutData'        => [
                'amount'   => $this->roundAmount($this->payoutAmount),
            ],
            'wallet'       => [
                'login' => $this->walletLogin,
                'fullName'    => $this->walletUserFullName,
            ],
            'recipient'     => $this->phoneNumber,
            'callbackUrl' => $this->callbackUrl,
            'merchantOrderId' => $this->merchantOrderId,
            'merchantOrderDescription' => $this->merchantOrderDescription
        ];
    }
}
