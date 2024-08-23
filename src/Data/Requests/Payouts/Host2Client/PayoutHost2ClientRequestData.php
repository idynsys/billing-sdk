<?php

namespace Idynsys\BillingSdk\Data\Requests\Payouts\Host2Client;

use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Data\Traits\TrafficTypeTrait;
use Idynsys\BillingSdk\Enums\RequestMethod;
use Idynsys\BillingSdk\Enums\TrafficType;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

abstract class PayoutHost2ClientRequestData extends RequestData
{
    use TrafficTypeTrait;

    // Наименование платежного метода
    protected string $paymentMethodName;

    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_POST;

    // URL из конфигурации для выполнения запроса
    protected string $urlConfigKeyForRequest = 'PAYOUT_URL';

    // Сумма депозита
    protected float $payoutAmount;

    // Код валюты депозита
    protected string $payoutCurrency;

    // Счет получателя или номер телефона
    protected string $recipient;

    // URL для передачи результата создания транзакции в B2B backoffice
    protected string $callbackUrl;

    // ID документа для создания депозита
    protected ?string $merchantOrderId;

    // описание документа для создания депозита
    protected ?string $merchantOrderDescription;

    public function __construct(
        float $payoutAmount,
        string $payoutCurrency,
        string $recipient,
        string $callbackUrl,
        ?string $merchantOrderId = null,
        ?string $merchantOrderDescription = null,
        string $trafficType = ''
    ) {
        parent::__construct();

        $this->payoutAmount = $payoutAmount;
        $this->payoutCurrency = $payoutCurrency;
        $this->recipient = $recipient;
        $this->callbackUrl = $callbackUrl;
        $this->merchantOrderId = $merchantOrderId;
        $this->merchantOrderDescription = $merchantOrderDescription;
        $this->setTrafficType($trafficType);

        $this->validateTrafficType();
    }
}
