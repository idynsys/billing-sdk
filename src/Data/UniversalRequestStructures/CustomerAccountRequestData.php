<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Data\UniversalRequestStructures\Traits\SubStructureRequestDataTrait;
use Idynsys\BillingSdk\Enums\BankName;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

class CustomerAccountRequestData implements RequestDataValidationContract
{
    use SubStructureRequestDataTrait;

    private ?string $pan;
    private ?string $bankName;

    public function __construct(?string $pan = null, ?string $bankName = null)
    {
        $this->pan = $pan;
        $this->bankName = $bankName;

        $this->responseProperties = ['pan', 'bankName'];
        self::$validationConfigKey = 'validations.customerAccounts';
    }

    public static function checkIfShouldBe(string $paymentType, string $communicationType, string $paymentMethod): void
    {
        if (self::getSpecificConfig($paymentType, $communicationType, $paymentMethod) !== false) {
            throw new BillingSdkException(
                'Customer account info must be presented for this method and must not be empty',
                422
            );
        }
    }

    /**
     * @throws BillingSdkException
     */
    public function validate(string $paymentType, string $communicationType, string $paymentMethod): void
    {
        $this->setCurrentConfig($paymentType, $communicationType, $paymentMethod);

        if ($this->required('pan') && empty($this->pan)) {
            throw new BillingSdkException('Bankcard number must not be empty and must be correct bankcard number', 422);
        }

        if ($this->required('bankName') && !$this->validateBankName()) {
            throw new BillingSdkException('Bank name is required, cannot be empty and must be in Bank List.', 422);
        }
    }

    private function validateBankName(): bool
    {
        if ($this->required('bankName')) {
            if (empty($this->bankName) || !in_array($this->bankName, BankName::values(), true)) {
                return false;
            }
        }

        return true;
    }
}
