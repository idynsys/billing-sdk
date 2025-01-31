<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

class BankCardData
{
    // Номер карты
    public string $cardNumber;

    // Наименование банка
    public string $bankName;

    // Время жизни карты в минутах
    public int $lifetimeInMinutes;

    public function __construct(array $info)
    {
        $this->cardNumber = $info['number'] ?? 'n/a';
        $this->bankName = $info['bankName'] ?? 'n/a';
        $this->lifetimeInMinutes = $info['lifetimeInMinutes'] ?? 0;
    }
}
