<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

class UniversalPayoutResponseData
{
    // Статус создания транзакции
    public string $status;

    // ID транзакции
    public string $transactionId;

    // Описание ошибки, если была при создании транзакции
    /** @var mixed|null */
    public $error;

    /**
     * @param string $status
     * @param string $transactionId
     * @param null|mixed $error
     */
    final public function __construct(
        string $status,
        string $transactionId,
               $error = null
    ) {
        $this->transactionId = $transactionId;
        $this->status = $status;
        $this->error = $error;
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
            $responseData['status'] ?? '',
            $responseData['id'] ?? '',
            $responseData['error'] ?? null
        );
    }
}
