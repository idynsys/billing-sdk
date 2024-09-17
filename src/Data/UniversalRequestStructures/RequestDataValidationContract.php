<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

interface RequestDataValidationContract
{
    public function validate(string $paymentType, string $communicationType, string $paymentMethod): void;
}
