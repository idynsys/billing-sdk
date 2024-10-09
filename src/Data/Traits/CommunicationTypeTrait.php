<?php

namespace Idynsys\BillingSdk\Data\Traits;

use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentType;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

trait CommunicationTypeTrait
{
    protected string $communicationType;

    public function setCommunicationType(string $communicationType): void
    {
        $this->communicationType = $communicationType;
    }

    public function validateCommunicationType(): void
    {
        if (in_array($this->communicationType, PaymentType::getValues())) {
            throw new BillingSdkException(
                'The Payment type ' . $this->communicationType . ' does not exist in '
                . implode(', ', PaymentType::getValues()),
                422
            );
        }
    }
}
