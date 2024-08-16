<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\RequestMethod;
use Idynsys\BillingSdk\Enums\TrafficType;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

/**
 * Абстрактный класс DTO для запроса на создание транзакции на вывод средств
 */
abstract class PayoutRequestData extends RequestData
{
    // Наименование платежного метода
    protected string $paymentMethodName = RequestMethod::METHOD_GET;

    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_POST;

    // URL из конфигурации для выполнения запроса
    protected string $urlConfigKeyForRequest = 'PAYOUT_URL';

    // Сумма депозита
    protected float $payoutAmount;

    // Код валюты депозита
    protected string $payoutCurrency;

    // Номер банковской карты
    protected string $cardNumber;

    // Месяц окончания действия карты
    protected string $cardExpiration;

    // Имя и Фамилия держателя карты
    protected string $cardRecipientInfo;

    // URL для передачи результата создания транзакции в B2B backoffice
    protected string $callbackUrl;

    // ID документа для создания депозита
    protected ?string $merchantOrderId;

    // описание документа для создания депозита
    protected ?string $merchantOrderDescription;

    protected string $trafficType;

    public function __construct(
        float $payoutAmount,
        string $payoutCurrency,
        string $cardNumber,
        string $cardExpiration,
        string $cardRecipientInfo,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        string $trafficType = '',
        ?ConfigContract $config = null
    ) {
        parent::__construct($config);

        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $payoutCurrency;
        $this->cardNumber = $cardNumber;
        $this->cardExpiration = $cardExpiration;
        $this->cardRecipientInfo = $cardRecipientInfo;
        $this->callbackUrl = $callbackUrl;
        $this->merchantOrderId = $merchantOrderId;
        $this->merchantOrderDescription = $merchantOrderDescription;
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
            throw new BillingSdkException('TrafficType must be empty string (""), "fdt" or "trusted".');
        }
    }
}
