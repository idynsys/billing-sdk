<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Data\UniversalRequestStructures\Validators\ValidationConfig;

class CustomerRequestData
{
    private string $id;

    private string $email;

    private string $phoneNumber;

    private ?string $bankName;

    private ?string $docId;

    public function __construct(
        string $id,
        string $email,
        string $phoneNumber,
        ?string $bankName = null,
        ?string $docId = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->bankName = $bankName;
        $this->docId = $docId;
    }

    public function getRequestData(string $paymentType, string $communicationType, string $paymentMethod): array
    {
        $config = ValidationConfig::getCustomerConfig($paymentType, $communicationType, $paymentMethod);

        if (!$config) {
            return [];
        }

        $resultData = [
            "id" => $this->id,
            "email" => $this->email,
            "phoneNumber" => $this->phoneNumber
        ];

        if (
            (!array_key_exists('ignore', $config) || !in_array('bankName', $config['ignore']))
            && $this->bankName
        ) {
            $resultData["bankName"] = $this->bankName;
        }

        if (
            (!array_key_exists('ignore', $config) || !in_array('docId', $config['ignore']))
            && $this->docId
        ) {
            $resultData["docId"] = $this->docId;
        }

        return $resultData;
    }
}
