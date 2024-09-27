<?php

declare(strict_types=1);

namespace Idynsys\BillingSdk\Validators;

use Idynsys\BillingSdk\Config;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

class ValidatorFactory
{
    /**
     * @param string $paymentType
     * @param string $paymentMethodName
     * @param string $communicationType
     * @return ValidatorContract
     * @throws BillingSdkException
     */
    public static function make(
        string $paymentType,
        string $paymentMethodName,
        string $communicationType
    ): ValidatorContract {
        $validatorClass = Config::getInstance()
            ->get('availableMethods.' . $paymentType . '.' . $communicationType . '.' . $paymentMethodName);

        if ($validatorClass === null) {
            throw new BillingSdkException(
                'The combination of payment method (' . $paymentMethodName . ') and communication type ('
                . $communicationType . ') for ' . $paymentType . 's is not supported.',
                422
            );
        }

        if (!class_exists($validatorClass)) {
            throw new BillingSdkException("Class $validatorClass is not found.", 500);
        }

        $validatorInstance = new $validatorClass($paymentType, $paymentMethodName, $communicationType);

        if (!$validatorInstance instanceof ValidatorContract) {
            throw new BillingSdkException("Class $validatorClass does not implement ValidatorContract.", 500);
        }

        return $validatorInstance;
    }
}
