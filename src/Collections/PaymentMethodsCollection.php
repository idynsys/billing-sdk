<?php

namespace Idynsys\BillingSdk\Collections;

use Idynsys\BillingSdk\Data\Entities\PaymentMethodData;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

/**
 * Класс для коллекции платежных методов
 */
class PaymentMethodsCollection extends Collection
{
    /**
     * Преобразование элемента перед вставкой в коллекцию
     *
     * @param $item
     * @return object|PaymentMethodData
     * @throws BillingSdkException
     */
    protected function itemConvert($item): object
    {
        $this->checkKeysExists($item, 'paymentMethodName', 'paymentMethodDescription');

        return new PaymentMethodData($item['paymentMethodName'], $item['paymentMethodDescription']);
    }
}