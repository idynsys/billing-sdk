<?php

declare(strict_types=1);

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures\Validators;

use Idynsys\BillingSdk\Enums\PaymentType;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

class ValidatorFactory
{
    private static $validators = [
        PaymentType::DEPOSIT => [
            'h2c|P2P' => ValidatorDepositHost2ClientP2P::class,
            'h2c|SBP' => ValidatorDepositHost2ClientSbp::class,
            'h2c|SberPay' => ValidatorDepositHost2ClientSberPay::class,
            'h2h|Bankcard' => ValidatorDepositHost2HostBankcard::class,
            'h2h|P2P' => ValidatorDepositHost2HostP2P::class,
        ],
        PaymentType::WITHDRAWAL => []
    ];

    public static function make(
        string $paymentType,
        string $paymentMethodName,
        string $communicationType
    ): ValidatorContract {
        $key = $communicationType . '|' . $paymentMethodName;

        if (!in_array($paymentType, array_keys(self::$validators))) {
            throw new BillingSdkException('Invalid payment type: ' . $paymentType, 422);
        }

        if (!isset(self::$validators[$paymentType][$key])) {
            throw new BillingSdkException(
                "Unsupported combination of payment method ($paymentMethodName) and communication type ($communicationType).",
                422
            );
        }

        $validatorClass = self::$validators[$paymentType][$key];

        return new $validatorClass($paymentMethodName, $communicationType);
    }
}
