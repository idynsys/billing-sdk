<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Data\Responses\PaymentDetails;

class UniversalDepositResponseData
{
    // Статус операции
    public string $status;

    // ID ордера
    public string $id;

    // Сумма транзакции
    public float $amount;

    // Валюта транзакции
    public string $currency;

    // URL для проведения транзакции при работе через H2C
    public ?string $redirectUrl;

    // Тип подтверждения транзакции
    public ?string $confirmationType;

    public ?PaymentDetails $paymentDetails = null;

    // Описание ошибки, если была при создании транзакции
    public $error;

    public function __construct(
        string $id,
        string $status,
        float $amount,
        string $currency,
        ?string $confirmationType = null,
        ?string $redirectUrl = null,
        ?PaymentDetails $paymentDetails = null,
        $error = null
    ) {
        $this->id = $id;
        $this->status = $status;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->confirmationType = $confirmationType;
        $this->redirectUrl = $redirectUrl;
        $this->paymentDetails = $paymentDetails;
        $this->error = $error;
    }

    /**
     * Создание DTO из данных, полученных после выполнения запроса
     *
     * @param array $responseData
     * @return self
     */
    public static function from(array $responseData): self
    {
        return new self(
            $responseData['id'] ?? 'n/a',
            $responseData['status'] ?? 'n/a',
            $responseData['amount'] ?? 0,
            $responseData['currency'] ?? 'n/a',
            $getResult['confirmationType'] ?? null,
            $responseData['redirectUrl'] ?? null,
            isset($responseData['paymentDetails']) ? new PaymentDetails($responseData['paymentDetails']) : null,
            $responseData['error'] ?? null
        );
    }
}
