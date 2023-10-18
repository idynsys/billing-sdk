<?php

namespace Idynsys\BillingSdk\Data;

trait WithAuthorizationToken
{
    private string $token;

    public function setToken($token): void
    {
        $this->token = $token;
    }

    protected function getHeadersData(): array
    {
        return $this->addAuthToken(parent::getHeadersData());
    }

    protected function addAuthToken(array $headerToken): array
    {
        $headerToken['Authorization'] = 'Bearer ' . $this->token;

        return $headerToken;
    }
}