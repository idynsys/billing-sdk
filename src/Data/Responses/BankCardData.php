<?php

namespace Idynsys\BillingSdk\Data\Responses;

class BankCardData
{
    public string $cardNumber;

    public string $bankName;

    public int $lifetimeInMinutes;

    public function __construct(array $info)
    {
        $this->cardNumber = $info['number'] ?? 'n/a';
        $this->bankName = $info['bank_name'] ?? 'n/a';
        $this->lifetimeInMinutes = $info['lifetime_in_minutes'] ?? 0;
    }
}