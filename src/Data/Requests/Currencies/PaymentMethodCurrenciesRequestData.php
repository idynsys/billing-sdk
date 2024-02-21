<?php

namespace Idynsys\BillingSdk\Data\Requests\Currencies;

use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\RequestMethod;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

/**
 * DTO для запроса списка валют платежных методов
 */
class PaymentMethodCurrenciesRequestData extends RequestData
{
    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_GET;

    // Ключ конфигурации с url запроса
    protected string $urlConfigKeyForRequest = 'PAYMENT_METHOD_CURRENCIES_URL';

    // Параметр: наименование платежного метода
    public string $paymentMethodName;

    /**
     * @throws BillingSdkException
     */
    public function __construct(string $methodName)
    {
        $this->paymentMethodName = $methodName;

        $this->validate();
    }

    /**
     * Проверку правильности заполнения параметров
     *
     * @return void
     * @throws BillingSdkException
     */
    protected function validate(): void
    {
        if (!in_array(
            $this->paymentMethodName,
            [PaymentMethod::P2P_NAME, PaymentMethod::BANKCARD_NAME, PaymentMethod::M_COMMERCE_NAME]
        )) {
            throw new BillingSdkException(
                'The value ' . $this->paymentMethodName . ' does not exist in '
                . implode(', ', PaymentMethod::getNames()), 422
            );
        }
    }

    /**
     * Получить параметры запроса
     *
     * @return string[]
     */
    protected function getRequestData(): array
    {
        return [
            'payment-method' => $this->paymentMethodName
        ];
    }
}