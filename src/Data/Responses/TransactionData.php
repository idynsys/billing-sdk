<?php

namespace Idynsys\BillingSdk\Data\Responses;

class TransactionData
{
    public string $id;
    public ?string $externalId;
    public string $paymentMethod;
    public ?string $paymentSystem;
    public string $transactionType;
    public ?float $amount;
    public ?string $currency;
    public string $status;

    public function __construct(
        string $id,
        ?string $externalId,
        string $paymentMethod,
        ?string $paymentSystem,
        string $transactionType,
        ?float $amount,
        ?string $currency,
        string $status
    ) {
        $this->id = $id;
        $this->externalId = $externalId;
        $this->paymentMethod = $paymentMethod;
        $this->paymentSystem = $paymentSystem;
        $this->transactionType = $transactionType;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->status = $status;
    }

    public static function from(array $getResult)
    {
        return new self(
            $getResult['id'] ?? null,
            $getResult['externalId'] ?? null,
            $getResult['paymentMethod'] ?? null,
            $getResult['paymentSystem'] ?? null,
            $getResult['transactionType'] ?? null,
            array_key_exists('amount', $getResult) && is_float($getResult['amount']) ? $getResult['amount'] : null,
            $getResult['currency'] ?? null,
            $getResult['status'] ?? null
        );
    }
}