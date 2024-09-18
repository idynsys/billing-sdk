<?php

namespace Idynsys\BillingSdk\Data\Responses;

/**
 * DTO ответа после оформления транзакции депозита
 */
class DepositResponseData
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

    // Данные банковской карты
    public ?BankCardData $card;

    // Описание ошибки, если была при создании транзакции
    public $error;

    public ?string $paymentType;

    public function __construct(
        string $id,
        string $status,
        float $amount,
        string $currency,
        ?string $paymentType = null,
        ?string $redirectUrl = null,
        ?string $confirmationType = null,
        ?BankCardData $card = null,
        $error = null
    ) {
        $this->id = $id;
        $this->status = $status;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->paymentType = $paymentType;
        $this->redirectUrl = $redirectUrl;
        $this->confirmationType = $confirmationType;
        $this->card = $card;
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
            $responseData['redirectUrl'] ?? ($responseData['redirect_url'] ?? null),
            $responseData['confirmationType'] ?? ($responseData['confirmation_type'] ?? null),
            array_key_exists('card', $responseData) && $responseData['card'] ? new BankCardData(
                $responseData['card']
            ) : null,
            $responseData['error'] ?? null
        );
    }
}
