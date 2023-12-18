<?php

namespace Idynsys\BillingSdk\Data\Entities;

/**
 * DTO для валюты
 */
class CurrencyData
{
    // код валюты
    public string $code;

    // наименование валюты
    public string $name;

    public function __construct(string $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }
}