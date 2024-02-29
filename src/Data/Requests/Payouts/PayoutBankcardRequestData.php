<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Enums\PaymentMethod;

/**
 * DTO запроса для создания вывода средств через платежный метод Bankcard
 */
class PayoutBankcardRequestData extends PayoutP2PRequestData
{
    // Параметр наименования платежного метода
    protected string $paymentMethodName = PaymentMethod::BANKCARD_NAME;
}