<?php

namespace Idynsys\BillingSdk\Data\Requests;

interface RequestDataContract
{
    public function getUrl(): string;

    public function getMethod(): string;

    public function getData(): array;
}
