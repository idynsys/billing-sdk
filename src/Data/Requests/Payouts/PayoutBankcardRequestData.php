<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutBankcardRequestData extends PayoutP2PRequestData
{
    protected string $paymentMethodId = PaymentMethod::BANKCARD_ID;

    protected string $paymentMethodName = PaymentMethod::BANKCARD_NAME;
}