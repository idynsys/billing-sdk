<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Data\UniversalRequestStructures\Validators\ValidationConfig;

class BankCardRequestData
{
    private string $pan;

    private string $holderName;

    private string $expiration;

    private ?string $cvv;

    public function __construct(
        string $pan,
        string $holderName,
        string $expiration,
        ?string $cvv = null
    ) {
        $this->pan = $pan;
        $this->holderName = $holderName;
        $this->expiration = $expiration;
        $this->cvv = $cvv;
    }

    public function getRequestData(string $paymentType, string $communicationType, string $paymentMethod): array
    {
        $config = ValidationConfig::getBankCardConfig($paymentType, $communicationType, $paymentMethod);

        if (!$config) {
            return [];
        }

        $resultData = [
            "pan" => $this->pan,
            "holderName" => $this->holderName,
            "expiration" => $this->expiration,
        ];

        if (!is_null($this->cvv)) {
            $resultData["cvv"] = $this->cvv;
        }

        return $resultData;
    }
}
