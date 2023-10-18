<?php

namespace Idynsys\BillingSdk\Config;

final class Config
{
    public const PROD_AUTH_URL = 'https://api-gateway.idynsys.org/api/api/user-access/token';
    public const PREPROD_AUTH_URL = 'https://api-gateway.preprod.idynsys.org/api/user-access/token';
    public const PREPROD_PAYMENT_METHODS_URL = 'https://api-gateway.preprod.idynsys.org/api/billing-settings/payment-methods';
    public const PROD_PAYMENT_METHODS_URL = 'https://api-gateway-dev-11357.dev.idynsys.org/api/billing-settings/application-payment-methods';
    public const PROD_PAY_IN_URL = 'https://api-gateway.preprod.idynsys.org/api/billing-settings/payment-methods';
    public const PREPROD_PAY_IN_URL = 'https://api-gateway.preprod.idynsys.org/api/billing-settings/payment-methods';
    public const PROD_PAYOUT_URL = 'https://api-gateway.preprod.idynsys.org/api/billing-settings/payment-methods';
    public const PREPROD_PAYOUT_URL = 'https://api-gateway.preprod.idynsys.org/api/billing-settings/payment-methods';
}