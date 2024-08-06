<?php

namespace Idynsys\BillingSdk\Enums;

class PaymentType
{
    public const DEPOSIT = 'deposit';
    public const WITHDRAWAL = 'withdrawal';

    public static function getNames()
    {
        return [
            self::DEPOSIT,
            self::WITHDRAWAL,
        ];
    }

    public static function getValues(): array
    {
        return [
            self::DEPOSIT,
            self::WITHDRAWAL,
        ];
    }
}
