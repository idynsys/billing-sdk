<?php

namespace Idynsys\BillingSdk\Data\Responses;

/**
 * DTO данных транзакции по ответу на запрос по состоянию транзакции
 */
class TransactionData
{
    // ID транзакции
    public string $id;

    public ?string $externalId;

    // Платежный метод транзакции
    public string $paymentMethod;

    // Наименование платежной системы
    public ?string $paymentSystem;

    // Тип транзакционной операции
    public string $transactionType;

    // Изначально запрошенная сумма
    public ?float $requestedAmount;

    // Изначально запрошенная валюта
    public ?string $requestedCurrency;

    // Проведенная сумма по транзакции
    public ?float $amount;

    // Валюта транзакции
    public ?string $currency;

    // Статус транзакции
    public string $status;

    // ID документа для создания депозита
    public ?string $merchantOrderId;

    public ?string $paymentType;

    public ?string $redirectUrl;

    public function __construct(
        string $id,
        ?string $externalId,
        string $paymentMethod,
        ?string $paymentSystem,
        string $transactionType,
        float $requestedAmount,
        string $requestedCurrency,
        ?float $amount,
        ?string $currency,
        string $status,
        ?string $merchantOrderId,
        ?string $paymentType,
        ?string $redirectUrl
    ) {
        $this->id = $id;
        $this->externalId = $externalId;
        $this->paymentMethod = $paymentMethod;
        $this->paymentSystem = $paymentSystem;
        $this->transactionType = $transactionType;
        $this->requestedAmount = $requestedAmount;
        $this->requestedCurrency = $requestedCurrency;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->status = $status;
        $this->merchantOrderId = $merchantOrderId;
        $this->paymentType = $paymentType;
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * Создание объекта DTO из массива полученных данных по запросу
     *
     * @param array $getResult
     * @return self
     */
    public static function from(array $getResult): self
    {
        return new self(
            $getResult['id'] ?? null,
            $getResult['externalId'] ?? null,
            $getResult['paymentMethod'] ?? null,
            $getResult['paymentSystem'] ?? null,
            $getResult['transactionType'] ?? null,
            static::getAmountResponseArray($getResult, 'requestedAmount', 0),
            $getResult['requestedCurrency'] ?? 'n/a',
            static::getAmountResponseArray($getResult, 'amount'),
            $getResult['currency'] ?? null,
            $getResult['status'] ?? null,
            $getResult['merchantOrderId'] ?? null,
            $getResult['paymentType'] ?? null,
            $getResult['redirectUrl'] ?? null
        );
    }

    /**
     * Получение суммы из массива данных по ключу
     *
     * @param array $data
     * @param string $key
     * @param float|null $default
     * @return float|null
     */
    private static function getAmountResponseArray(array &$data, string $key, ?float $default = null): ?float
    {
        return (array_key_exists($key, $data) && is_numeric($data[$key])) ? (float) $data[$key] : $default;
    }
}
