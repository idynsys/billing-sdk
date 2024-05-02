<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts\Host2Client;

use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutSbpHost2ClientRequestData extends PayoutP2PHost2ClientRequestData
{
    protected string $paymentMethodName = PaymentMethod::SBP_NAME;
}
