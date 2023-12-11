<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutBankcardRequestData extends PayoutP2PRequestData
{
    protected string $paymentMethodId = PaymentMethod::Bankcard_id;

    protected string $paymentMethodName = PaymentMethod::Bankcard_name;
}