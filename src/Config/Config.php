<?php

namespace Idynsys\BillingSdk\Config;

/**
 * Класс содержит константы, используемые при отправке запросов в B2B backoffice
 */
final class Config
{
    // url для получения токена аутентификации на продакшн
    public const PROD_AUTH_URL = 'https://api-gateway.idynsys.org/api/api/user-access/token';

    // url для получения токена аутентификации на препродакшн для тестирования
    public const PREPROD_AUTH_URL = 'https://api-gateway.preprod.idynsys.org/api/user-access/token';

    // url для получения списка платежных методов на препродакшн для тестирования
    public const PREPROD_PAYMENT_METHODS_URL = 'https://api-gateway.preprod.idynsys.org/api/billing-settings/payment-methods-by-app';

    // url для получения списка платежных методов на продакшн
    public const PROD_PAYMENT_METHODS_URL = 'https://api-gateway.idynsys.org/api/billing-settings/application-payment-methods';

    // url для создания депозита на продакшн
    public const PROD_DEPOSIT_URL = 'http://api-gateway.idynsys.org/api/payment-gateway/api/payments';

    // url для создания депозита на препродакшн для тестирования
    public const PREPROD_DEPOSIT_URL = 'https://api-gateway-dev-11546.dev.idynsys.org/api/accounts/api/payments';

    // url для создания запроса на вывод средств на продакшн
    public const PROD_PAYOUT_URL = 'https://api-gateway.idynsys.org/api/accounts/api/payouts';

    // url для создания запроса на вывод средств на препродакшн для тестирования
    public const PREPROD_PAYOUT_URL = 'https://api-gateway.preprod.idynsys.org/api/accounts/api/payouts';

    // url для возврата на страницу, когда депозит выполнен на продакшн
    public const PREPROD_DEPOSIT_CALLBACK = 'https://admin-panel.preprod.idynsys.org/billing/organization-balance-deposit-success';

    // url для возврата на страницу, когда депозит выполнен на препродакшн для тестирования
    public const PROD_DEPOSIT_CALLBACK = 'https://admin-panel.idynsys.org/billing/organization-balance-deposit-success';
}