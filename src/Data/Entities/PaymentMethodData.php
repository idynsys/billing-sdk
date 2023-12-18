<?php

namespace Idynsys\BillingSdk\Data\Entities;

/**
 * DTO платежного метода
 */
class PaymentMethodData
{
    // Наименование метода
    public string $name;

    // Описание метода
    public string $description;

    public function __construct(string $name, ?string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }
}