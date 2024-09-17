<?php

namespace Idynsys\BillingSdk\Enums;

class CommunicationType
{
    public const HOST_2_HOST = 'h2h';
    public const HOST_2_CLIENT = 'h2c';

    public static function getValues(): array
    {
        return [
            self::HOST_2_HOST,
            self::HOST_2_CLIENT
        ];
    }
}
