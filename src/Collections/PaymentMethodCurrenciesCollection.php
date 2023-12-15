<?php

namespace Idynsys\BillingSdk\Collections;

use Idynsys\BillingSdk\Data\Entities\CurrencyData;

class PaymentMethodCurrenciesCollection extends Collection
{
    protected function itemConvert($item): object
    {
        $this->checkKeysExists($item, 'currencyName', 'currencyIsoCode');
        return new CurrencyData($item['currencyName'], $item['currencyIsoCode']);
    }
}