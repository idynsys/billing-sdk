<?php

namespace Idynsys\BillingSdk\Data\Traits;

use Idynsys\BillingSdk\Enums\TrafficType;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

trait TrafficTypeTrait
{
    // Тип трафика
    protected string $trafficType;

    protected function setTrafficType(string $trafficType): void
    {
        $this->trafficType = $trafficType;
    }

    protected function validateTrafficType()
    {
        if (
            $this->trafficType !== '' &&
            $this->trafficType !== TrafficType::FDT &&
            $this->trafficType !== TrafficType::TRUSTED
        ) {
            throw new BillingSdkException('TrafficType must be empty string (""), "fdt" or "trusted".', 422);
        }
    }

    protected function addTrafficTypeToRequestData()
    {
        return (empty($this->trafficType)) ? [] : ['traffic_type' => $this->trafficType];
    }
}
