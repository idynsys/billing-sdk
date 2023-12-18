<?php

namespace Idynsys\BillingSdk\Data\Responses;

class DepositMCommerceConfirmedResponseData
{
    // Статус операции
    public string $status;

    // Ошибка, если статус ERROR
    public $error;

    public function __construct(string $status, $error = null)
    {
        $this->status = $status;
        $this->error = $error;
    }

    public static function from(array $responseData): self
    {
        return new self($responseData['status'] ?? 'NOT DEFINED', $responseData['error'] ?? null);
    }
}