<?php

namespace Idynsys\BillingSdk\Data\Requests\Transactions;

use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\RequestMethod;

/**
 * DTO для запроса для получения информации по транзакции
 */
class TransactionRequestData extends RequestData
{
    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_GET;

    // URL из конфигурации для выполнения запроса
    protected string $urlConfigKeyForRequest = 'TRANSACTION_DATA_URL';

    // Параметр ID транзакции
    public string $transactionId;

    public function __construct(string $transactionId, ?ConfigContract $config = null)
    {
        parent::__construct($config);

        $this->transactionId = $transactionId;
    }

    /**
     * Модификация URL запроса
     *
     * @return string
     */
    public function getUrl(): string
    {
        return parent::getUrl() . '/' . $this->transactionId;
    }
}
