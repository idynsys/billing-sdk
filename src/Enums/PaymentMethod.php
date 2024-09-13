<?php

namespace Idynsys\BillingSdk\Enums;

class PaymentMethod
{
    public const P2P_NAME = 'P2P';

    public const BANKCARD_NAME = 'Bankcard';

    public const M_COMMERCE_NAME = 'MCommerce';

    public const SBER_PAY_NAME = 'SberPay';

    public const SBP_NAME = 'SBP';

    public const SBP_QR_NAME = 'SBP-QR';

    public const E_MANAT = 'eManat';

    public const SMART_CARD = 'SmartCard';

    public const IN_CARD_P2P = 'inCardP2P';

    public const M10 = 'm10';

    public const PAYFIX = 'Payfix';

    public const HAVALE = 'Havale';

    public const HAYHAY = 'Hayhay';

    public const PEP = 'Pep';

    public const PAYCO = 'PayCo';

    public const PAPARA = 'Papara';

    /**
     * Получить список имен доступных платежных методов
     * @return string[]
     */
    public static function getNames(): array
    {
        return [
            self::P2P_NAME,
            self::BANKCARD_NAME,
            self::M_COMMERCE_NAME,
            self::SBER_PAY_NAME,
            self::SBP_NAME,
            self::E_MANAT,
            self::SMART_CARD,
            self::IN_CARD_P2P,
            self::M10,
            self::PAYFIX,
            self::HAVALE,
            self::HAYHAY,
            self::PEP,
            self::PAYCO,
            self::PAPARA
        ];
    }

    public static function getValues(): array
    {
        return [
            self::P2P_NAME,
            self::BANKCARD_NAME,
            self::M_COMMERCE_NAME,
            self::SBP_NAME,
            self::SBER_PAY_NAME,
            self::E_MANAT,
            self::SMART_CARD,
            self::IN_CARD_P2P,
            self::M10,
            self::PAYFIX,
            self::HAVALE,
            self::HAYHAY,
            self::PEP,
            self::PAYCO,
            self::PAPARA
        ];
    }
}
