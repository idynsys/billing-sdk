<?php

return[
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

    // url для создания запроса на вывод средств
    'PAYOUT_URL' => '/accounts/api/payouts',

    // url для получения данных по транзакции
    'TRANSACTION_DATA_URL' => '/accounts/api/transactions',

    // url для получения данных по транзакции
    'PAYMENT_METHOD_CURRENCIES_URL' => '/pay-aggregator/external-app/currencies',

    // подтверждение платежа через мобильную коммерцию
    'DEPOSIT_M_COMMERCE_CONFIRM_URL' => '/accounts/api/payments/{transaction}/confirmMobilePayment',
];
