<?php

namespace Idynsys\BillingSdk\Data\Responses;

class DepositResponseData
{
    public string $paymentStatus;
    public string $transactionId;
    public float $amount;
    public string $currency;
    public ?string $redirectUrl;
    public ?array $card;
    public ?array $destinationCard;

    public function __construct(
        string $transactionId,
        string $paymentStatus,
        float $amount,
        string $currency,
        ?string $redirectUrl = null,
        ?array $card = null,
        ?array $destinationCard = null
    )
    {
        $this->transactionId = $transactionId;
        $this->paymentStatus = $paymentStatus;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->redirectUrl = $redirectUrl;
        $this->card = $card;
        $this->destinationCard = $destinationCard;
    }

    public static function from(array $responseData): self
    {
        return new self(
            $responseData['transaction_id'] ?? 'n/a',
            $responseData['payment_status'],
            $responseData['amount'] ?? 0,
            $responseData['currency'] ?? 'n/a',
            $responseData['redirect_url'] ?? null,
            $responseData['card'] ?? null,
            $responseData['destination_card'] ?? null,
        );
    }
}