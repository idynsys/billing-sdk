<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Config\Config;

final class AuthRequestData implements RequestData
{
    public string $userName;
    public string $password;
    public string $clientId;

    public function __construct(string $userName, string $password)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->clientId = $this->getClientId();
    }

    private function getClientId(): string
    {
        return getenv('BILLING_SDK_CLIENT_ID') ?: '';
    }

    public function getUrl(): string
    {
        return getenv('BILLING_SDK_MODE') === 'PRODUCTION' ? Config::PROD_AUTH_URL : Config::PREPROD_AUTH_URL;
    }

    public function getData(): array
    {
        return [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
                'client_id'  => $this->clientId,
                'username'   => $this->userName,
                'password'   => $this->password,
                'grant_type' => 'password'
            ]
        ];
    }
}