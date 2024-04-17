<?php

namespace Idynsys\BillingSdk\Config;

interface ConfigContract
{
    public function get(string $key, mixed $default = null);

    public function set(string $key, $value): void;
}
