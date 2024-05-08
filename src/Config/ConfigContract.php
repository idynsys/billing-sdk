<?php

namespace Idynsys\BillingSdk\Config;

interface ConfigContract
{
    public function get(string $key, $default = null): ?string;

    public function set(string $key, $value): void;
}
