<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Exceptions\BillingSdkException;

class PaymentRequestData implements RequestDataValidationContract
{
    private float $amount;

    private string $currency;

    public function __construct(
        float $amount,
        string $currency
    ) {
        $this->amount = round($amount, 2);
        $this->currency = strtoupper($currency);
    }

    public function getRequestData(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency
        ];
    }

    public function validate(string $paymentType, string $communicationType, string $paymentMethod): void
    {
        if ($this->amount <= 0) {
            throw new BillingSdkException('Amount must be greater 0.00', 422);
        }

        if (empty($this->currency) || strlen($this->currency) !== 3) {
            throw new BillingSdkException('Currency code must be 3 symbols', 422);
        }
    }
}
