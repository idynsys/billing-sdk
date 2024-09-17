<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Exceptions\BillingSdkException;

class MerchantOrderRequestData implements RequestDataValidationContract
{
    private string $id;

    private ?string $description;

    public function __construct(
        string $id,
        string $description
    ) {
        $this->id = $id;
        $this->description = $description;
    }

    public function getRequestData()
    {
        return [
            "id" => $this->id,
            "description" => $this->description
        ];
    }

    public function validate(string $paymentType, string $communicationType, string $paymentMethod): void
    {
        if (empty($this->id)) {
            throw new BillingSdkException('Merchant order ID must not be empty', 422);
        }

        if (empty($this->description)) {
            throw new BillingSdkException('Merchant order description must not be empty', 422);
        }
    }
}
