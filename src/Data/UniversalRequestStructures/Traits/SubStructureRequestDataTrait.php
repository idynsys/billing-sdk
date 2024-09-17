<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures\Traits;

trait SubStructureRequestDataTrait
{
    private array $config = [];

    /**
     * @var bool|array
     */
    private $currentConfig = false;

    /**
     * @var array
     */
    private array $resultData = [];

    abstract protected function setConfig(): void;

    private array $responseProperties = [];

    public function setCurrentConfig(string $paymentType, string $communicationType, string $paymentMethod): void
    {
        if ($this->currentConfig) {
            return;
        }

        $this->setConfig();

        if (key_exists($paymentType, $this->config)) {
            if (key_exists($communicationType, $this->config[$paymentType])) {
                if (key_exists($paymentMethod, $this->config[$paymentType][$communicationType])) {
                    $this->currentConfig = $this->config[$paymentType][$communicationType][$paymentMethod];
                }
            }
        }
    }

    private function inIgnore(string $propertyName): bool
    {
        return array_key_exists('ignore', $this->currentConfig) &&
            in_array($propertyName, $this->currentConfig['ignore']);
    }

    private function inOnly(string $propertyName): bool
    {
        return !array_key_exists('only', $this->currentConfig) ||
            in_array($propertyName, $this->currentConfig['only']);
    }

    private function required(string $propertyName): bool
    {
        return array_key_exists('required', $this->currentConfig) &&
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
