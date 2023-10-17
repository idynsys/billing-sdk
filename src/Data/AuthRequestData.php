<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Config\Config;

final class AuthRequestData extends RequestData
{

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
            'client_id' => $this->getClientId(),
            'grant_type' => 'password',
            'username' => 'admin@test.com',
            'password' => '123456'
        ];
    }
}