<?php

namespace Idynsys\BillingSdk\Enums;

final class TrafficType
{
    public const FTD = 'ftd'; // первичный трафик (для непроверенных пользователей делающих оплату первый раз)

    public const TRUSTED = 'trusted'; // для доверенных пользователей

    public static function getValues(): array
    {
        return [
            self::FTD,
            self::TRUSTED,
        ];
    }
}
