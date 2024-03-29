<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits;

use Idynsys\BillingSdk\Enums\PaymentMethod;

/**
 * @deprecated
 * Использовать ./v2/DepositBankcardRequestData
 *
 * DTO запроса для создания депозита через платежный метод Bankcard
 */
class DepositBankcardRequestData extends DepositP2PRequestData
{
    // Параметр наименования платежного метода
    protected string $paymentMethodName = PaymentMethod::BANKCARD_NAME;
}