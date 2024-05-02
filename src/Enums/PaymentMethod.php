<?php

namespace Idynsys\BillingSdk\Enums;

class PaymentMethod
{
    public const P2P_NAME = 'P2P';

    public const BANKCARD_NAME = 'Bankcard';

    public const M_COMMERCE_NAME = 'MCommerce';

    public const SBER_PAY_NAME = 'SberPay';

    public const SBP_NAME = 'SBP';

    /**
     * Получить список имен доступных платежных методов
     * @return string[]
     */
    public static function getNames(): array
    {
        return [
            self::P2P_NAME,
            self::BANKCARD_NAME,
            self::M_COMMERCE_NAME
        ];
    }

    public static function getValues(): array
    {
        return [self::P2P_NAME, self::BANKCARD_NAME, self::M_COMMERCE_NAME, self::SBP_NAME, self::SBER_PAY_NAME];
    }
}
