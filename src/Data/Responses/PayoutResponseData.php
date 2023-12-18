<?php

namespace Idynsys\BillingSdk\Data\Responses;

/**
 * DTO класс ответ после оформления транзакции на вывод средств
 */
class PayoutResponseData
{
    // ID транзакции
    public string $transactionId;

    public function __construct(string $transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * Создание DTO из данных ответа, полученных на запрос
     *
     * @param array $responseData
     * @return self
     */
    public static function from(array $responseData): self
    {
        return new static(
            $responseData['transactionId'] ?? ''
        );
    }
}