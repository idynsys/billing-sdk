<?php

use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\PaymentType;

return [
    // Идентификатор клиента
    'clientId' => getenv('BILLING_SDK_CLIENT_ID') ?: '',

    // Секретный ключ клиента
    'clientSecret' => getenv('BILLING_SDK_APPLICATION_SECRET_KEY') ?: '',

    // Режим работы приложения с пакетом
    'mode' => getenv('BILLING_SDK_MODE') ?: 'DEVELOPMENT',

    // продакшн хост
    'prod_host' => 'https://api-gateway.idynsys.org/api',

    // тестовый хост
    'sendbox_host' => 'https://api-gateway.sandbox.idynsys.org/api',

    // хост для разработки или динамо тестов
    'dev_host' => getenv('BILLING_DEV_HOST') ?: 'https://api-gateway.sandbox.idynsys.org/api',

    // url для получения токена аутентификации
    'AUTH_URL' => '/user-access/token',

    // url для получения списка платежных методов
    'PAYMENT_METHODS_URL' => '/pay-aggregator/external-app/payment-methods',

    // url для создания депозита
    'DEPOSIT_URL' => '/accounts/api/payments',
    'UNIVERSAL_DEPOSIT_URL' => '/accounts/deposit',

    // url для создания запроса на вывод средств
    'PAYOUT_URL' => '/accounts/api/payouts',

    // url для получения данных по транзакции
    'TRANSACTION_DATA_URL' => '/accounts/api/transactions',

    // url для получения данных по транзакции
    'PAYMENT_METHOD_CURRENCIES_URL' => '/pay-aggregator/external-app/currencies',

    // подтверждение платежа через мобильную коммерцию
    'DEPOSIT_M_COMMERCE_CONFIRM_URL' => '/accounts/api/payments/{transaction}/confirmMobilePayment',

    'validations' => [
        'map' => [
            'Idynsys\BillingSdk\Data\UniversalRequestStructures\BankCardRequestData' => 'bankcards',
            'Idynsys\BillingSdk\Data\UniversalRequestStructures\UrlsRequestData' => 'urls',
            'Idynsys\BillingSdk\Data\UniversalRequestStructures\CustomerRequestData' => 'customers',
        ],
        'bankcards' => [
            PaymentType::DEPOSIT => [
                CommunicationType::HOST_2_CLIENT => [
                    PaymentMethod::P2P_NAME => false,
                    PaymentMethod::SBP_NAME => false,
                    PaymentMethod::SBER_PAY_NAME => false,
                ],
                CommunicationType::HOST_2_HOST => [
                    PaymentMethod::BANKCARD_NAME => [
                        'required' => ['cvv']
                    ],
                    PaymentMethod::P2P_NAME => false,
                ]
            ],
        ],
        'urls' => [
            PaymentType::DEPOSIT => [
                CommunicationType::HOST_2_CLIENT => [
                    PaymentMethod::P2P_NAME => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::SBP_NAME => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::SBER_PAY_NAME => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                ],
                CommunicationType::HOST_2_HOST => [
                    PaymentMethod::BANKCARD_NAME => [
                        'ignore' => ['return', 'redirectSuccess', 'redirectFail'],
                    ],
                    PaymentMethod::P2P_NAME => [
                        'ignore' => ['return', 'redirectSuccess', 'redirectFail'],
                    ],
                ]
            ],
        ],
        'customers' => [
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
        ]
    ]
];
