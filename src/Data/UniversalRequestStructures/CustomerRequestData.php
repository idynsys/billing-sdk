<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Data\UniversalRequestStructures\Traits\SubStructureRequestDataTrait;
use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\PaymentType;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

class CustomerRequestData implements RequestDataValidationContract
{
    use SubStructureRequestDataTrait;

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

        $this->responseProperties = ['id', 'email', 'phoneNumber', 'bankName', 'docId'];
        self::$validationConfigKey = 'validations.customers';
    }

    public function validate(string $paymentType, string $communicationType, string $paymentMethod): void
    {
        $this->setCurrentConfig($paymentType, $communicationType, $paymentMethod);

        if (empty($this->email) || !$this->validateEmail()) {
            throw new BillingSdkException('Email must be a valid email address and can not be empty', 422);
        }

        if (empty($this->phoneNumber) || !$this->validatePhoneNumber()) {
            throw new BillingSdkException('Phone number must be valid and can not be empty', 422);
        }


        if (!$this->inIgnore('bankName') && $this->required('bankName') && empty($this->bankName)) {
            throw new BillingSdkException('Bank name is required and cannot be empty.', 422);
        }

        if (!$this->inIgnore('docId') && $this->required('docId') && empty($this->bankName)) {
            throw new BillingSdkException('Document ID is required and cannot be empty.', 422);
        }
    }

    private function validateEmail(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function validatePhoneNumber(): bool
    {
        $cleanedPhoneNumber = str_replace([' ', '-'], '', $this->phoneNumber);
        $pattern = '/^\+?[1-9]\d{1,14}$/';

        return preg_match($pattern, $cleanedPhoneNumber) === 1;
    }
}
