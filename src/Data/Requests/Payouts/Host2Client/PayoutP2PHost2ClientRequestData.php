<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts\Host2Client;

use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutP2PHost2ClientRequestData extends PayoutHost2ClientRequestData
{
    protected string $paymentMethodName = PaymentMethod::P2P_NAME;

    /**
     * Получить данные для запроса
     *
     * @return array
     */
    protected function getRequestData(): array
    {
        return [
            "paymentMethodName" => $this->paymentMethodName,
            'communicationType' => CommunicationType::HOST_2_CLIENT,
            'payoutData' => [
                'amount' => $this->roundAmount($this->payoutAmount),
                'currency' => $this->payoutCurrency
            ],
            'recipient' => $this->recipient,
            'callbackUrl' => $this->callbackUrl,
            'merchantOrderId' => $this->merchantOrderId,
            'merchantOrderDescription' => $this->merchantOrderDescription
        ];
    }
}
