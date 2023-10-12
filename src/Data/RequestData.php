<?php

namespace Idynsys\BillingSdk\Data;

interface RequestData
{
    public function getUrl(): string;

    public function getData(): array;
}