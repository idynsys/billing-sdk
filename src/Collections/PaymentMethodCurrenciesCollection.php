<?php

namespace Idynsys\BillingSdk\Collections;

use Idynsys\BillingSdk\Data\Entities\CurrencyData;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

/**
 * Класс для коллекции валют платежно метода
 */
class PaymentMethodCurrenciesCollection extends Collection
{
    /**
     * Преобразование элемента коллекции перед вставкой в коллекцию
     *
     * @param $item
     * @return object|CurrencyData
     * @throws BillingSdkException
     */
    protected function itemConvert($item): object
    {
        $this->checkKeysExists($item, 'currencyName', 'currencyIsoCode');

        return new CurrencyData($item['currencyName'], $item['currencyIsoCode']);
    }
}