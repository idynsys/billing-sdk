<?php

namespace Idynsys\BillingSdk\Data\Responses;

class PayoutResponseData
{
    public string $transactionId;

    public function __construct(string $transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public static function from(array $responseData): self
    {
        return new static(
            $responseData['transactionId'] ?? ''
        );
    }
}