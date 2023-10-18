<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Config\Config;
use Idynsys\BillingSdk\Enums\RequestMethod;

class PayInRequestData extends RequestData implements AuthorisationTokenInclude
{
    use WithAuthorizationToken;

    protected string $requestMethod = RequestMethod::METHOD_GET;

    public function getUrl(): string
    {
        return getenv('BILLING_SDK_MODE') === 'PRODUCTION'
            ? Config::PROD_PAY_IN_URL : Config::PREPROD_PAY_IN_URL;
    }

    protected function getRequestData(): array
    {
        return [];
    }
}