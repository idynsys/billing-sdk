<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits;

use Idynsys\BillingSdk\Enums\PaymentMethod;

/**
 * DTO запроса для создания депозита через платежный метод Bankcard
 */
class DepositBankcardRequestData extends DepositP2PRequestData
{
    // Параметр ID платежного метода
    protected string $paymentMethodId = PaymentMethod::BANKCARD_ID;

    // Параметр наименования платежного метода
    protected string $paymentMethodName = PaymentMethod::BANKCARD_NAME;
}