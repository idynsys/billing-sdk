<?php

namespace Idynsys\BillingSdk\Data\Requests\Auth;

interface ClientIdInclude
{
    public function setClientId(string $clientId): void;

    public function getClientId(): string;
}