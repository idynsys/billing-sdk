<?php

namespace Idynsys\BillingSdk\Collections;

use Idynsys\BillingSdk\Data\Entities\PaymentMethodData;

class PaymentMethodsCollection extends Collection
{
    protected function itemConvert($item): object
    {
        $this->checkKeysExists($item, 'paymentMethodName', 'paymentMethodDescription');

        return new PaymentMethodData($item['paymentMethodName'], $item['paymentMethodDescription']);
    }
}