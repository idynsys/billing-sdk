<?php

namespace Idynsys\BillingSdk\Data\Requests\Auth;

trait WithClientId
{
    private string $clientId;

    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }
}