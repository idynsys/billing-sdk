<?php

namespace Idynsys\BillingSdk;

use Idynsys\BillingSdk\Config\ConfigContract;

class Config implements ConfigContract
{
    private static ?Config $instance = null;

    private array $config;

    private function __construct()
    {
        $this->loadConfig();
    }

    private function loadConfig(): void
    {
        $this->config = require __DIR__ . '/Config/config.php';
    }

    final public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get(string $key, $default = null): ?string
    {
        $instance = self::getInstance();

        return array_key_exists($key, $instance->config) ? $instance->config[$key] : $default;
    }

    public function set(string $key, $value): void
    {
        $instance = self::getInstance();

        $instance->config[$key] = $value;
    }
}
