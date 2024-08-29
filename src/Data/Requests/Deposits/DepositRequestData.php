<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Data\Traits\TrafficTypeTrait;
use Idynsys\BillingSdk\Enums\RequestMethod;
use Idynsys\BillingSdk\Enums\TrafficType;

/**
 * Абстрактный класс DTO для всех запроса на создание транзакции депозита
 */
abstract class DepositRequestData extends RequestData
{
    use TrafficTypeTrait;

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

    // IP адрес пользователя, выполняющего оформление депозита
    protected string $userIpAddress;

    // UserAgent от пользователя
    protected string $userAgent;

    // Accept-Language пользователя
    protected string $acceptLanguage;

    // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    protected string $fingerprint;

    public function __construct(string $trafficType = TrafficType::FDT, ?ConfigContract $config = null)
    {
        parent::__construct($config);

        $this->setTrafficType($trafficType);
        $this->validateTrafficType();
    }
}
