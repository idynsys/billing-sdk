<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits\v2;

use Idynsys\BillingSdk\Enums\PaymentMethod;

/**
 * DTO запроса для создания депозита через платежный метод Bankcard
 */
class DepositBankcardRequestData extends DepositP2PRequestData
{
    // Параметр наименования платежного метода
    protected string $paymentMethodName = PaymentMethod::BANKCARD_NAME;
}