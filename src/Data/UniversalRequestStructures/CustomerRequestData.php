<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Data\UniversalRequestStructures\Traits\SubStructureRequestDataTrait;
use Idynsys\BillingSdk\Enums\BankName;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

class CustomerRequestData implements RequestDataValidationContract
{
    use SubStructureRequestDataTrait;

    private string $id;

    private string $email;

    private ?string $phoneNumber;

    private ?string $bankName;

    private ?string $docId;

    public function __construct(
        string $id,
        string $email,
        ?string $phoneNumber = null,
        ?string $bankName = null,
        ?string $docId = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->bankName = $bankName;
        $this->docId = $docId;

        $this->responseProperties = ['id', 'email', 'phoneNumber', 'bankName', 'docId'];
    }

    public function validate(string $paymentType, string $communicationType, string $paymentMethod): void
    {
        $this->setCurrentConfig($paymentType, $communicationType, $paymentMethod);

        if (empty($this->id)) {
            throw new BillingSdkException('Customer Id value can not be empty', 422);
        }

        if (empty($this->email) || !$this->validateEmail()) {
            throw new BillingSdkException('Email must be a valid email address and can not be empty', 422);
        }

        if (!$this->inIgnore('phoneNumber') && !$this->validatePhoneNumber()) {
            throw new BillingSdkException('Phone number must be valid and can not be empty', 422);
        }


        if (!$this->inIgnore('bankName') && !$this->validateBankName()) {
            throw new BillingSdkException('Bank name is required, cannot be empty and must be in Bank List.', 422);
        }

        if (!$this->inIgnore('docId') && $this->required('docId') && empty($this->docId)) {
            throw new BillingSdkException('Document ID is required and cannot be empty.', 422);
        }
    }

    private function validateEmail(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function validatePhoneNumber(): bool
    {
        if ($this->required('phoneNumber') || $this->phoneNumber !== null) {
            if ($this->phoneNumber === null || empty($this->phoneNumber)) {
                return false;
            }

            $cleanedPhoneNumber = str_replace([' ', '-'], '', $this->phoneNumber);
            $pattern = '/^\+?[1-9]\d{1,14}$/';

            return preg_match($pattern, $cleanedPhoneNumber) === 1;
        }

        return true;
    }

    private function validateBankName(): bool
    {
        if ($this->required('bankName')) {
            if (empty($this->bankName) || !in_array($this->bankName, BankName::values())) {
                return false;
            }
        }

        return true;
    }
}
