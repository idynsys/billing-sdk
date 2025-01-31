<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;

/**
 * @deprecated
 * Не поддерживается с выходом версии 5.0. Нужно пользоваться универсальным методом создания транзакции
 *
 * DTO запроса для создания транзакции на вывод средств через платежный метод P2P
 */
class PayoutP2PRequestData extends PayoutRequestData
{
    // Параметр наименование платежного метода
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
            'communicationType' => CommunicationType::HOST_2_HOST,
            'payoutData' => [
                'amount' => $this->roundAmount($this->payoutAmount),
                'currency' => $this->payoutCurrency
            ],
            'cardData' => [
                'pan' => $this->cardNumber,
                'expiration' => $this->cardExpiration,
                'recipientInfo' => $this->cardRecipientInfo
            ],
            'customerData' => [
                'id' => $this->userId
            ],
            'callbackUrl' => $this->callbackUrl,
            'merchantOrderId' => $this->merchantOrderId,
            'merchantOrderDescription' => $this->merchantOrderDescription,
        ];
    }
}
