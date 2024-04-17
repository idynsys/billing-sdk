<?php

namespace Idynsys\BillingSdk\Config;

interface ConfigContract
{
    public function get(string $key, mixed $default = null): null|string;

    public function set(string $key, mixed $value): void;
}
