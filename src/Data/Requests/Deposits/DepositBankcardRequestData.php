<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits;

use Idynsys\BillingSdk\Enums\PaymentMethod;

class DepositBankcardRequestData extends DepositP2PRequestData
{
    protected string $paymentMethodId = PaymentMethod::BANKCARD_ID;

    protected string $paymentMethodName = PaymentMethod::BANKCARD_NAME;
}