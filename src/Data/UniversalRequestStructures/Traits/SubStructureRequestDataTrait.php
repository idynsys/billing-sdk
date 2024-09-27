<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures\Traits;

use Idynsys\BillingSdk\Config;

trait SubStructureRequestDataTrait
{
    private array $config = [];

    /**
     * @var bool|array
     */
    private $currentConfig = false;

    protected static string $validationConfigKey = 'validations.subKey';

    /**
     * @var array
     */
    private array $resultData = [];

    protected function setConfig(): void
    {
        $this->currentConfig = self::getValidationConfig();
    }

    static public function setValidationConfigKey(): void
    {
        $subKey = Config::getInstance()->get('validations.map.' . __CLASS__, 'subKey');

        self::$validationConfigKey = 'validations.' . $subKey;
    }

    private array $responseProperties = [];

    public static function getValidationConfig()
    {
        return Config::getInstance()->get(self::$validationConfigKey);
    }

    public static function getSpecificConfig(string $paymentType, string $communicationType, string $paymentMethod)
    {
        $config = self::getValidationConfig();

        if (!is_array($config) || !isset($config[$paymentType][$communicationType][$paymentMethod])) {
            return false;
        }

        return $config[$paymentType][$communicationType][$paymentMethod];
    }

    public function setCurrentConfig(string $paymentType, string $communicationType, string $paymentMethod): void
    {
        if ($this->currentConfig) {
            return;
        }

        $this->setConfig();
        $this->currentConfig = self::getSpecificConfig($paymentType, $communicationType, $paymentMethod);
    }

    private function inIgnore(string $propertyName): bool
    {
        return is_array($this->currentConfig) && array_key_exists('ignore', $this->currentConfig) &&
            in_array($propertyName, $this->currentConfig['ignore']);
    }

    private function inOnly(string $propertyName): bool
    {
        return !is_array($this->currentConfig) || !array_key_exists('only', $this->currentConfig) ||
            in_array($propertyName, $this->currentConfig['only']);
    }

    private function required(string $propertyName): bool
    {
        return is_array($this->currentConfig) && array_key_exists('required', $this->currentConfig) &&
            in_array($propertyName, $this->currentConfig['required']);
    }

    private function addPropertyInRequestDataByConfig(string $propertyName): void
    {
        if ((!($this->inIgnore($propertyName)) && $this->inOnly($propertyName)) && $this->{$propertyName}) {
            $this->resultData[$propertyName] = $this->{$propertyName};
        }
    }

    public function getRequestData(string $paymentType, string $communicationType, string $paymentMethod): array
    {
        $this->setCurrentConfig($paymentType, $communicationType, $paymentMethod);

        if ($this->currentConfig === false || !is_array($this->currentConfig)) {
            return [];
        }

        foreach ($this->responseProperties as $propertyName) {
            $this->addPropertyInRequestDataByConfig($propertyName);
        }

        return $this->resultData;
    }
}
