<?php

namespace Idynsys\BillingSdk\Data\Entities;

class CurrencyData
{
    public string $code;

    public string $name;

    public function __construct(string $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }
}