<?php

namespace Idynsys\BillingSdk\Enums;

class PaymentMethod
{
    const P2P_NAME = 'P2P';

    const P2P_ID = 'ee8f772f-0e43-4b40-bcc4-e3768290248e';

    const BANKCARD_ID = '0a9f8c59-20a4-47a1-9ac2-4ca2048a728a';

    const BANKCARD_NAME = 'Bankcard';

    const M_COMMERCE_ID = '99803784-7ace-49e1-8ab7-18e4d0f9d58b';

    const M_COMMERCE_NAME = 'MCommerce';

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
}