<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Enums\PaymentMethod;

class PayoutP2PRequestData extends PayoutRequestData
{
    protected string $paymentMethodId = PaymentMethod::P2P_ID;

    protected string $paymentMethodName = PaymentMethod::P2P_NAME;

    /**
     * Получить данные для запроса
     *
     * @return array
     */
    protected function getRequestData(): array
    {
        return [
            'paymentMethodId'   => $this->paymentMethodId,
            "paymentMethodName" => $this->paymentMethodName,
            'payoutData'        => [
                'amount'   => $this->roundAmount($this->payoutAmount),
                'currency' => $this->payoutCurrency
            ],
            'cardData'          => [
                'pan'           => $this->cardNumber,
                'expiration'    => $this->cardExpiration,
                'recipientInfo' => $this->cardRecipientInfo
            ],
            'callbackUrl' => $this->callbackUrl
        ];
    }
}