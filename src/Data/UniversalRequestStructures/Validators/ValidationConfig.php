<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures\Validators;

use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\PaymentType;

class ValidationConfig
{
    private const CUSTOMER_CONFIG = [
        PaymentType::DEPOSIT => [
            CommunicationType::HOST_2_CLIENT => [
                PaymentMethod::P2P_NAME => [
                    'ignore' => ['bankName', 'docId'],
                ],
                PaymentMethod::SBP_NAME => [
                    'ignore' => ['bankName', 'docId'],
                ],
                PaymentMethod::SBER_PAY_NAME => [
                    'ignore' => ['bankName', 'docId'],
                ]
            ],
            CommunicationType::HOST_2_HOST => [
                PaymentMethod::BANKCARD_NAME => [
                    'ignore' => ['bankName', 'docId']
                ],
                PaymentMethod::P2P_NAME => [
                    'ignore' => ['bankName', 'docId']
                ],
            ]
        ],
    ];

    private const BANK_CARD_CONFIG = [
        PaymentType::DEPOSIT => [
            CommunicationType::HOST_2_CLIENT => [
                PaymentMethod::P2P_NAME => false,
                PaymentMethod::SBP_NAME => false,
                PaymentMethod::SBER_PAY_NAME => false,
            ],
            CommunicationType::HOST_2_HOST => [
                PaymentMethod::BANKCARD_NAME => false,
                PaymentMethod::P2P_NAME => false,
            ]
        ],
    ];

    private const URLS_CONFIG = [
        PaymentType::DEPOSIT => [
            CommunicationType::HOST_2_CLIENT => [
                PaymentMethod::P2P_NAME => [
                    'ignore' => ['redirectSuccess', 'redirectFail'],
                ],
                PaymentMethod::SBP_NAME => [
                    'ignore' => ['redirectSuccess', 'redirectFail'],
                ],
                PaymentMethod::SBER_PAY_NAME => [
                    'ignore' => ['redirectSuccess', 'redirectFail'],
                ],
            ],
            CommunicationType::HOST_2_HOST => [
                PaymentMethod::BANKCARD_NAME => [
                    'ignore' => ['redirectSuccess', 'redirectFail'],
                ],
                PaymentMethod::P2P_NAME => [
                    'ignore' => ['redirectSuccess', 'redirectFail'],
                ],
            ]
        ],
    ];

    public static function getCustomerConfig(string $paymentType, string $communicationType, string $paymentMethod)
    {
        $result = false;

        if (key_exists($paymentType, self::CUSTOMER_CONFIG)) {
            if (key_exists($communicationType, self::CUSTOMER_CONFIG[$paymentType])) {
                if (key_exists($paymentMethod, self::CUSTOMER_CONFIG[$paymentType][$communicationType])) {
                    $result = self::CUSTOMER_CONFIG[$paymentType][$communicationType][$paymentMethod];
                }
            }
        }

        return $result;
    }


    public static function getBankCardConfig(string $paymentType, string $communicationType, string $paymentMethod)
    {
        $result = false;

        if (key_exists($paymentType, self::BANK_CARD_CONFIG)) {
            if (key_exists($communicationType, self::BANK_CARD_CONFIG[$paymentType])) {
                if (key_exists($paymentMethod, self::BANK_CARD_CONFIG[$paymentType][$communicationType])) {
                    $result = self::BANK_CARD_CONFIG[$paymentType][$communicationType][$paymentMethod];
                }
            }
        }

        return $result;
    }

    public static function getUrlsConfig(string $paymentType, string $communicationType, string $paymentMethod)
    {
        $result = false;

        if (key_exists($paymentType, self::URLS_CONFIG)) {
            if (key_exists($communicationType, self::URLS_CONFIG[$paymentType])) {
                if (key_exists($paymentMethod, self::URLS_CONFIG[$paymentType][$communicationType])) {
                    $result = self::URLS_CONFIG[$paymentType][$communicationType][$paymentMethod];
                }
            }
        }

        return $result;
    }
}
