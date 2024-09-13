<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client;

use Idynsys\BillingSdk\Enums\PaymentMethod;

class DepositSbpQRHost2ClientRequestData extends DepositSbpHost2ClientRequestData
{
    // Параметр наименования платежного метода
    protected string $paymentMethodName = PaymentMethod::SBP_QR_NAME;
}
