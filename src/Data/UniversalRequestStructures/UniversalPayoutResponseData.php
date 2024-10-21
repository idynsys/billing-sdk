<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

class UniversalPayoutResponseData
{
    // Статус создания транзакции
    public string $status;

    // ID транзакции
    public string $id;

    // Описание ошибки, если была при создании транзакции
    /** @var mixed|null */
    public $error;

    /**
     * @param string $status
     * @param string $id
     * @param null|mixed $error
     */
    final public function __construct(
        string $status,
        string $id,
        $error = null
    ) {
        $this->id = $id;
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
