<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

class PaymentRequestData
{
    private float $amount;

    private string $currency;

    public function __construct(
        float $amount,
        string $currency
    ) {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getRequestData(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency
        ];
    }
}
