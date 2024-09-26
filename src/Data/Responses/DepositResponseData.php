<?php

namespace Idynsys\BillingSdk\Data\Responses;

/**
 * DTO ответа после оформления транзакции депозита
 */
class DepositResponseData
{
    // Статус операции
    public string $paymentStatus;

    // ID созданной транзакции
    public string $transactionId;

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

    // Пока всегда null
    public ?array $destinationCard;

    // Описание ошибки, если была при создании транзакции
    public $error;

    public ?string $paymentType;

    public function __construct(
        string $transactionId,
        string $paymentStatus,
        float $amount,
        string $currency,
        ?string $redirectUrl = null,
        ?string $confirmationType = null,
        ?BankCardData $card = null,
        ?array $destinationCard = null,
        ?string $paymentType = null,
        $error = null
    ) {
        $this->transactionId = $transactionId;
        $this->paymentStatus = $paymentStatus;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->redirectUrl = $redirectUrl;
        $this->confirmationType = $confirmationType;
        $this->card = $card;
        $this->destinationCard = $destinationCard;
        $this->paymentType = $paymentType;
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
            $responseData['transaction_id'] ?? 'n/a',
            $responseData['payment_status'] ?? 'n/a',
            $responseData['amount'] ?? 0,
            $responseData['currency'] ?? 'n/a',
            $responseData['redirectUrl'] ?? ($responseData['redirect_url'] ?? null),
            $responseData['confirmationType'] ?? ($responseData['confirmation_type'] ?? null),
            array_key_exists('card', $responseData) && $responseData['card'] ? new BankCardData(
                $responseData['card']
            ) : null,
            $responseData['destination_card'] ?? null,
            $getResult['paymentType'] ?? null,
            $responseData['error'] ?? null
        );
    }
}
