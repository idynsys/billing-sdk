<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Config\Config;
use Idynsys\BillingSdk\Enums\RequestMethod;

final class AuthRequestData extends RequestData
{
    protected string $requestMethod = RequestMethod::METHOD_POST;

    private function getClientId(): string
    {
        return getenv('BILLING_SDK_CLIENT_ID') ?: '';
    }

    public function getUrl(): string
    {
        return getenv('BILLING_SDK_MODE') === 'PRODUCTION' ? Config::PROD_AUTH_URL : Config::PREPROD_AUTH_URL;
    }

    protected function getRequestData(): array
    {
        return [
            'clientId' => $this->getClientId(),
        ];
    }
}