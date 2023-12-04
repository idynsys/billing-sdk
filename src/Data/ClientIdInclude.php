<?php

namespace Idynsys\BillingSdk\Data;

interface ClientIdInclude
{
    public function setClientId(string $clientId): void;

    public function getClientId(): string;
}