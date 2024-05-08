<?php

namespace Idynsys\BillingSdk\Config;

interface ConfigContract
{
    /**
     * @param string $key
     * @param mixed $default
     * @return string|null
     */
    public function get(string $key, $default = null): ?string;

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void;
}
