<?php

namespace Idynsys\BillingSdk\Data\Responses;

class PaymentDetails
{
    public ?string $bankAccount = null;
    public ?string $phoneNumber = null;
    public ?string $bankName = null;
    public ?string $holderName = null;
    public ?int $lifetimeInMinutes = null;
    public ?string $pan = null;

    public function __construct(array $attributes)
    {
        $this->bankAccount = $attributes['bankAccount'] ?? 'n/a';
        $this->phoneNumber = $attributes['phoneNumber'] ?? 'n/a';
        $this->bankName = $attributes['bankName'] ?? 'n/a';
        $this->holderName = $attributes['holderName'] ?? 'n/a';
        $this->lifetimeInMinutes = isset($attributes['lifeTimeInMinutes'])
            ? (int)$attributes['lifeTimeInMinutes'] : null;
        $this->pan = $attributes['pan'] ?? 'n/a';
    }
}
