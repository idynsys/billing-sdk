<?php

namespace Idynsys\BillingSdk\Data\Traits;

use Idynsys\BillingSdk\Enums\BankName;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

trait BankNameTrait
{
    // Наименование банка получателя перевода
    protected string $bankName;

    protected function setBankName(string $bankName): void
    {
        $this->bankName = $bankName;
    }

    protected function validateBankName(): void
    {
        if (!in_array($this->bankName, BankName::values())) {
            throw new BillingSdkException('Bank name has incorrect value.', 422);
        }
    }
}
