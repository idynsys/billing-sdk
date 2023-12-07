<?php

namespace Idynsys\BillingSdk\Collections;

use Idynsys\BillingSdk\Data\Entities\PaymentMethodData;

class PaymentMethodsCollection extends Collection
{
    public function addItems(array $items, ?string $key = null): Collection
    {
        if ($key) {
            $items = array_key_exists($key, $items) ? $items[$key] : [];
        }

        foreach ($items as $item) {
            $this->checkKeysExists($item, 'id', 'paymentMethodName', 'paymentMethodDescription');

            $this->addItem(
                new PaymentMethodData($item['id'], $item['paymentMethodName'], $item['paymentMethodDescription'])
            );
        }

        return $this;
    }


}