<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client;


use Idynsys\BillingSdk\Enums\PaymentMethod;

/**
 * DTO запроса для создания депозита Host2Client через платежный метод P2P
 */
class DepositSberPay2PHostClientRequestData extends DepositP2PHost2ClientRequestData
{
    // Наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::SBER_PAY_NAME;
}
