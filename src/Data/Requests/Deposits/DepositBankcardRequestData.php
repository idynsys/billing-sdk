<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits;

use Idynsys\BillingSdk\Enums\PaymentMethod;

class DepositBankcardRequestData extends DepositP2PRequestData
{
    protected string $paymentMethodId = PaymentMethod::Bankcard_id;

    protected string $paymentMethodName = PaymentMethod::Bankcard_name;
}