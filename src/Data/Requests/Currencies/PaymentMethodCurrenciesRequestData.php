<?php

namespace Idynsys\BillingSdk\Data\Requests\Currencies;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Data\Traits\PaymentTypeTrait;
use Idynsys\BillingSdk\Data\Traits\TrafficTypeTrait;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\RequestMethod;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

/**
 * DTO для запроса списка валют платежных методов
 */
class PaymentMethodCurrenciesRequestData extends RequestData
{
    use TrafficTypeTrait;
    use PaymentTypeTrait;

    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_GET;

    // Ключ конфигурации с url запроса
    protected string $urlConfigKeyForRequest = 'PAYMENT_METHOD_CURRENCIES_URL';

    // Параметр: наименование платежного метода
    public string $paymentMethodName;

    // сумма платежа
    public ?float $amount;

    /**
     * @throws BillingSdkException
     */
    public function __construct(
        string $methodName,
        ?float $amount = null,
        ?string $paymentType = null,
        ?string $trafficType = null,
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->paymentMethodName = $methodName;
        $this->amount = $amount;
        $this->setPaymentType($paymentType);
        $this->setTrafficType($trafficType);

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
        if (!in_array($this->paymentMethodName, PaymentMethod::getValues())) {
            throw new BillingSdkException(
                'The Payment method name ' . $this->paymentMethodName . ' does not exist in '
                . implode(', ', PaymentMethod::getNames()),
                422
            );
        }

        $this->validatePaymentType();
        if ($this->trafficType !== null) {
            $this->validateTrafficType();
        }
    }

    /**
     * Получить параметры запроса
     *
     * @return string[]
     */
    protected function getRequestData(): array
    {
        $data = [
            'paymentMethod' => $this->paymentMethodName
        ];

        if ($this->amount !== null) {
            $data['amount'] = $this->amount;
        }

        if ($this->paymentType !== null) {
            $data['paymentType'] = $this->paymentType;
        }

        if ($this->trafficType !== null) {
            $data['trafficType'] = $this->trafficType;
        }

        return $data;
    }
}
