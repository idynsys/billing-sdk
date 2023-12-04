<?php

namespace Idynsys\BillingSdk;

class Application
{
    // Идентификатор клиента (приложения)
    private string $clientId;

    // Секретный ключ доступа для клиента (приложения)
    private string $clientSecret;

    private Config $config;
    public function __construct(?string $clientId, ?string $clientSecret)
    {
        $this->setClientId($clientId);
        $this->setClientSecret($clientSecret);
        $this->config = new Config();
    }

    private function setClientId(?string $clientId): void
    {
        if ($clientId) {
            $this->clientId = $clientId;
        } else {
            $this->clientId = getenv('BILLING_SDK_CLIENT_ID') ?: '';
        }
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientSecret(?string $clientSecret): void
    {
        if ($clientSecret) {
            $this->clientSecret = $clientSecret;
        } else {
            $this->clientSecret = getenv('BILLING_SDK_APPLICATION_SECRET_KEY') ?: '';
        }
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }
}