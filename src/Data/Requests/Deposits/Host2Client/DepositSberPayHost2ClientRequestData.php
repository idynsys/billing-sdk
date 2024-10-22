<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client;


use Idynsys\BillingSdk\Enums\PaymentMethod;

/**
 * @deprecated
 * Не поддерживается с выходом версии 5.0. Нужно пользоваться универсальным методом создания транзакции
 *
 * DTO запроса для создания депозита через платежный метод SberPayHost2Client
 */
class DepositSberPayHost2ClientRequestData extends DepositP2PHost2ClientRequestData
{
    // Наименование платежного метода
    protected string $paymentMethodName = PaymentMethod::SBER_PAY_NAME;
}
