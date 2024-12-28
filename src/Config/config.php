<?php

use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\PaymentType;
use Idynsys\BillingSdk\Validators\ValidatorDeposit;
use Idynsys\BillingSdk\Validators\ValidatorWithdrawal;

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
    'UNIVERSAL_WITHDRAWAL_URL' => '/accounts/withdrawal',

    // url для получения данных по транзакции
    'TRANSACTION_DATA_URL' => '/accounts/api/transactions',

    // url для получения данных по транзакции
    'PAYMENT_METHOD_CURRENCIES_URL' => '/pay-aggregator/external-app/currencies',

    // подтверждение платежа через мобильную коммерцию
    'DEPOSIT_M_COMMERCE_CONFIRM_URL' => '/accounts/api/payments/{transaction}/confirmMobilePayment',

    // Определение платежных методов и классов для валидации для универсальных методов
    'availableMethods' => [

        // Методы для депозита
        PaymentType::DEPOSIT => [
            CommunicationType::HOST_2_HOST => [
                PaymentMethod::P2P_NAME => ValidatorDeposit::class,
                PaymentMethod::BANKCARD_NAME => ValidatorDeposit::class,
                PaymentMethod::BANK_ACCOUNT => ValidatorDeposit::class,
                PaymentMethod::SBP_NAME => ValidatorDeposit::class,
            ],
            CommunicationType::HOST_2_CLIENT => [
                PaymentMethod::P2P_NAME => ValidatorDeposit::class,
                PaymentMethod::SBP_NAME => ValidatorDeposit::class,
                PaymentMethod::SBP_QR_NAME => ValidatorDeposit::class,
                PaymentMethod::SBER_PAY_NAME => ValidatorDeposit::class,
                PaymentMethod::E_MANAT => ValidatorDeposit::class,
                PaymentMethod::IN_CARD_P2P => ValidatorDeposit::class,
                PaymentMethod::M10 => ValidatorDeposit::class,
                PaymentMethod::SMART_CARD => ValidatorDeposit::class,
            ],
        ],

        // Методы для вывода средств
        PaymentType::WITHDRAWAL => [
            CommunicationType::HOST_2_HOST => [
                PaymentMethod::SBER_PAY_NAME => ValidatorWithdrawal::class,
                PaymentMethod::P2P_NAME => ValidatorWithdrawal::class,
                PaymentMethod::SBP_NAME => ValidatorWithdrawal::class,
                PaymentMethod::BANK_ACCOUNT => ValidatorWithdrawal::class,
                PaymentMethod::E_MANAT => ValidatorWithdrawal::class,
                PaymentMethod::IN_CARD_P2P => ValidatorWithdrawal::class,
                PaymentMethod::M10 => ValidatorWithdrawal::class,
                PaymentMethod::SMART_CARD => ValidatorWithdrawal::class,
            ],
            CommunicationType::HOST_2_CLIENT => [],
        ],
    ],

    // Настройки для проверки данных
    'validations' => [
        // сопоставление класса-подструктуры данных и ключа конфигурации
        // Ключи конфигурации находятся ниже: bankcards, urls, customers
        'map' => [
            'Idynsys\BillingSdk\Data\UniversalRequestStructures\BankCardRequestData' => 'bankcards',
            'Idynsys\BillingSdk\Data\UniversalRequestStructures\UrlsRequestData' => 'urls',
            'Idynsys\BillingSdk\Data\UniversalRequestStructures\CustomerRequestData' => 'customers',
            'Idynsys\BillingSdk\Data\UniversalRequestStructures\CustomerAccountRequestData' => 'customerAccounts',
        ],

        // Конфигурация для данных банковской карты
        'bankcards' => [
            PaymentType::DEPOSIT => [
                CommunicationType::HOST_2_CLIENT => [
                    PaymentMethod::P2P_NAME => false,
                    PaymentMethod::SBP_NAME => false,
                    PaymentMethod::SBP_QR_NAME => false,
                    PaymentMethod::SBER_PAY_NAME => false,
                    PaymentMethod::E_MANAT => false,
                    PaymentMethod::IN_CARD_P2P => false,
                    PaymentMethod::M10 => false,
                    PaymentMethod::SMART_CARD => false,
                ],
                CommunicationType::HOST_2_HOST => [
                    PaymentMethod::BANKCARD_NAME => [
                        'required' => ['cvv']
                    ],
                    PaymentMethod::P2P_NAME => false,
                    PaymentMethod::BANK_ACCOUNT => false,
                    PaymentMethod::SBP_NAME => false,
                ]
            ],
            PaymentType::WITHDRAWAL => [
                CommunicationType::HOST_2_CLIENT => [
                    //
                ],
                CommunicationType::HOST_2_HOST => [
                    PaymentMethod::SBER_PAY_NAME => [
                        'ignore' => ['cvv']
                    ],
                    PaymentMethod::P2P_NAME => [
                        'ignore' => ['cvv']
                    ],
                    PaymentMethod::SBP_NAME => false,
                    PaymentMethod::BANK_ACCOUNT => false,
                    PaymentMethod::E_MANAT => false,
                    PaymentMethod::IN_CARD_P2P => [
                        'ignore' => ['holderName', 'cvv'],
                    ],
                    PaymentMethod::M10 => false,
                    PaymentMethod::SMART_CARD => [
                        'ignore' => ['holderName', 'cvv'],
                    ],
                ]
            ]
        ],

        //  Конфигурация для данных urls
        'urls' => [
            PaymentType::DEPOSIT => [
                CommunicationType::HOST_2_CLIENT => [
                    PaymentMethod::P2P_NAME => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::SBP_NAME => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::SBP_QR_NAME => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::SBER_PAY_NAME => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::E_MANAT => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::IN_CARD_P2P => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::M10 => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::SMART_CARD => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                ],
                CommunicationType::HOST_2_HOST => [
                    PaymentMethod::BANKCARD_NAME => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::P2P_NAME => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::BANK_ACCOUNT => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::SBP_NAME => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                ]
            ],
            PaymentType::WITHDRAWAL => [
                CommunicationType::HOST_2_CLIENT => [],
                CommunicationType::HOST_2_HOST => [
                    PaymentMethod::SBER_PAY_NAME => [],
                    PaymentMethod::P2P_NAME => [],
                    PaymentMethod::SBP_NAME => [],
                    PaymentMethod::BANK_ACCOUNT => [],
                    PaymentMethod::E_MANAT => [],
                    PaymentMethod::IN_CARD_P2P => [],
                    PaymentMethod::M10 => [],
                    PaymentMethod::SMART_CARD => [],
                ]
            ]
        ],

        // Конфигурация для данных пользователя
        'customers' => [
            PaymentType::DEPOSIT => [
                CommunicationType::HOST_2_CLIENT => [
                    PaymentMethod::P2P_NAME => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId', 'fullName'],
                    ],
                    PaymentMethod::SBP_NAME => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId', 'fullName'],
                    ],
                    PaymentMethod::SBP_QR_NAME => [
                        'ignore' => ['bankName', 'docId', 'fullName'],
                        'required' => ['phoneNumber']
                    ],
                    PaymentMethod::SBER_PAY_NAME => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId', 'fullName'],
                    ],
                    PaymentMethod::E_MANAT => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId'],
                        'required' => ['fullName']
                    ],
                    PaymentMethod::IN_CARD_P2P => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId'],
                        'required' => ['fullName']
                    ],
                    PaymentMethod::M10 => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId'],
                        'required' => ['fullName']
                    ],
                    PaymentMethod::SMART_CARD => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId'],
                        'required' => ['fullName']
                    ],
                ],
                CommunicationType::HOST_2_HOST => [
                    PaymentMethod::BANKCARD_NAME => [
                        'ignore' => ['bankName', 'docId', 'fullName'],
                        'required' => ['phoneNumber']
                    ],
                    PaymentMethod::P2P_NAME => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId', 'fullName']
                    ],
                    PaymentMethod::BANK_ACCOUNT => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId', 'fullName']
                    ],
                    PaymentMethod::SBP_NAME => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId', 'fullName']
                    ],
                ]
            ],
            PaymentType::WITHDRAWAL => [
                CommunicationType::HOST_2_CLIENT => [],
                CommunicationType::HOST_2_HOST => [
                    PaymentMethod::SBER_PAY_NAME => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId', 'fullName']
                    ],
                    PaymentMethod::P2P_NAME => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId', 'fullName']
                    ],
                    PaymentMethod::SBP_NAME => [
                        'required' => ['phoneNumber', 'bankName', 'fullName'],
                        'ignore' => ['docId']
                    ],
                    PaymentMethod::BANK_ACCOUNT => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId', 'fullName']
                    ],
                    PaymentMethod::E_MANAT => [
                        'ignore' => ['bankName', 'docId'],
                        'required' => ['phoneNumber', 'fullName']
                    ],
                    PaymentMethod::IN_CARD_P2P => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId'],
                        'required' => ['fullName']
                    ],
                    PaymentMethod::M10 => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId'],
                        'required' => ['fullName']
                    ],
                    PaymentMethod::SMART_CARD => [
                        'ignore' => ['phoneNumber', 'bankName', 'docId'],
                        'required' => ['fullName']
                    ],
                ]
            ]
        ],

        'customerAccounts' => [
            PaymentType::WITHDRAWAL => [
                CommunicationType::HOST_2_HOST => [
                    PaymentMethod::BANK_ACCOUNT => [
                        'required' => ['pan'],
                        'ignore' => ['bankName'],
                    ],
                    PaymentMethod::M10 => [
                        'required' => ['pan'],
                        'ignore' => ['bankName'],
                    ],
                ]
            ],
        ],
    ],
];
