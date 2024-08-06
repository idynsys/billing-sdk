<?php

namespace Idynsys\BillingSdk\Data\Requests\Currencies;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\PaymentType;
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

    // сумма платежа
    public ?float $amount;

    // тип платежа
    public ?string $paymentType;

    /**
     * @throws BillingSdkException
     */
    public function __construct(
        string $methodName,
        ?float $amount = null,
        ?string $paymentType = null,
        ?ConfigContract $config = null)
    {
        parent::__construct($config);

        $this->paymentMethodName = $methodName;
        $this->amount = $amount;
        $this->paymentType = $paymentType;

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
        if (!in_array($this->paymentMethodName, PaymentMethod::getValues()))
        {
            throw new BillingSdkException(
                'The Payment method name ' . $this->paymentMethodName . ' does not exist in '
                . implode(', ', PaymentMethod::getNames()),
                422
            );
        }

        if ($this->paymentType !== null && !in_array($this->paymentType, PaymentType::getValues())) {
            throw new BillingSdkException(
                'The Payment type ' . $this->paymentType . ' does not exist in '
                . implode(', ', PaymentType::getNames()),
                422
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
            'paymentMethod' => $this->paymentMethodName,
            'amount' => $this->amount,
            'paymentType' => $this->paymentType,
        ];
    }
}
