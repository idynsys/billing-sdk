<?php

declare(strict_types=1);

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures\Validators;

use Idynsys\BillingSdk\Exceptions\BillingSdkException;

class ValidatorFactory
{
    private static $validators = [
        'h2c|P2P' => ValidatorHost2ClientP2P::class,
        'h2c|SBP' => ValidatorHost2ClientSBP::class,
        'h2c|SberPay' => ValidatorHost2ClientSberPay::class,
        'h2h|Bankcard' => ValidatorHost2HostBankcard::class,
        'h2h|P2P' => ValidatorHost2HostP2P::class,
    ];

    public static function getValidator(string $paymentMethodName, string $communicationType): ValidatorContract
    {
        $key = $communicationType . '|' . $paymentMethodName;

        if (!isset(self::$validators[$key])) {
            throw new BillingSdkException(
                "Unsupported combination of payment method ($paymentMethodName) and communication type ($communicationType)."
            );
        }

        return new self::$validators[$key]();
    }
}
