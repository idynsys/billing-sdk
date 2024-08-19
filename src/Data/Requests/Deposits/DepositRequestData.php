<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\RequestMethod;
use Idynsys\BillingSdk\Enums\TrafficType;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

/**
 * Абстрактный класс DTO для всех запроса на создание транзакции депозита
 */
abstract class DepositRequestData extends RequestData
{
    // Наименование платежного метода
    protected string $paymentMethodName;

    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_POST;

    // URL из конфигурации для выполнения запроса
    protected string $urlConfigKeyForRequest = 'DEPOSIT_URL';

    // ID документа для создания депозита
    protected ?string $merchantOrderId = null;

    // описание документа для создания депозита
    protected ?string $merchantOrderDescription = null;

    // email пользователя совершающего операцию
    protected string $customerEmail;

    // Сумма депозита
    protected float $paymentAmount;

    // Код валюты депозита
    protected string $paymentCurrencyCode;

    // URL для передачи результата создания транзакции в B2B backoffice
    protected string $callbackUrl;

    protected string $trafficType;

    public function __construct(string $trafficType = '', ?ConfigContract $config = null)
    {
        parent::__construct($config);

        $this->trafficType = $trafficType;
        $this->validateTrafficType();
    }

    protected function validateTrafficType()
    {
        if (
            $this->trafficType !== '' &&
            $this->trafficType !== TrafficType::FDT &&
            $this->trafficType !== TrafficType::TRUSTED
        ) {
            throw new BillingSdkException('TrafficType must be empty string (""), "fdt" or "trusted".', 422);
        }
    }
}
