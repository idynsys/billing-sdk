<?php

namespace Idynsys\BillingSdk\Data\Traits;

use Idynsys\BillingSdk\Enums\PaymentType;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

trait PaymentTypeTrait
{
    // Тип платежа
    protected ?string $paymentType;

    protected function setPaymentType(?string $paymentType)
    {
        $this->paymentType = $paymentType;
    }

    protected function validatePaymentType()
    {
        if ($this->paymentType !== null && !in_array($this->paymentType, PaymentType::getValues())) {
            throw new BillingSdkException(
                'The Payment type ' . $this->paymentType . ' does not exist in '
                . implode(', ', PaymentType::getNames()),
                422
            );
        }
    }
}
