<?php

namespace Idynsys\BillingSdk\Config;

final class Config
{
    public const PROD_AUTH_URL = 'https://sso.prod.idynsys.org/realms/b2b/protocol/openid-connect/token';
    public const PREPROD_AUTH_URL = 'https://sso.preprod.idynsys.org/realms/b2b/protocol/openid-connect/token';
    public const PREPROD_PAYMENT_METHODS_URL = 'https://api-gateway-dev-11357.dev.idynsys.org/api/billing-settings/application-payment-methods';

}