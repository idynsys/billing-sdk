<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts\Host2Client;

use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutSberPayHost2ClientRequestData extends PayoutP2PHost2ClientRequestData
{
    protected string $paymentMethodName = PaymentMethod::SBER_PAY_NAME;
}
