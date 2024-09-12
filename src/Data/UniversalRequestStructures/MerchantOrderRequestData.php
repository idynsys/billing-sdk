<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

class MerchantOrderRequestData
{
    private string $id;

    private ?string $description;

    public function __construct(
        string $id,
        ?string $description = null
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
}
