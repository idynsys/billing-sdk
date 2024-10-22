<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client;

use Idynsys\BillingSdk\Enums\PaymentMethod;

/**
 * @deprecated
 * Не поддерживается с выходом версии 5.0. Нужно пользоваться универсальным методом создания транзакции
 *
 *  DTO запроса для создания депозита через платежный метод SBP-QR Host2Client
 */
class DepositSbpQRHost2ClientRequestData extends DepositSbpHost2ClientRequestData
{
    // Параметр наименования платежного метода
    protected string $paymentMethodName = PaymentMethod::SBP_QR_NAME;
}
