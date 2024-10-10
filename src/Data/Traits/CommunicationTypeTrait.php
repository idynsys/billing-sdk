<?php

namespace Idynsys\BillingSdk\Data\Traits;

use Idynsys\BillingSdk\Enums\CommunicationType;
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
        if (in_array($this->communicationType, CommunicationType::getValues())) {
            throw new BillingSdkException(
                'The Communication type ' . $this->communicationType . ' does not exist in '
                . implode(', ', CommunicationType::getValues()),
                422
            );
        }
    }
}
