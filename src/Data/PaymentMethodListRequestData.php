<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Config\Config;

use Idynsys\BillingSdk\Enums\RequestMethod;

class PaymentMethodListRequestData extends RequestData implements AuthorisationTokenInclude
{
    use WithAuthorizationToken;

    protected string $requestMethod = RequestMethod::METHOD_GET;

    public function getUrl(): string
    {
        return getenv(
            'BILLING_SDK_MODE'
        ) === 'PRODUCTION' ? Config::PROD_PAYMENT_METHODS_URL : Config::PREPROD_PAYMENT_METHODS_URL;
    }

    protected function getRequestData(): array
    {
        return [];
    }
}