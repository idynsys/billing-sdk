<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Data\UniversalRequestStructures\Validators\ValidationConfig;

class UrlsRequestData
{
    private string $callback;

    private ?string $return;

    private ?string $redirectSuccess;

    private ?string $redirectFail;

    public function __construct(
        string $callback,
        ?string $return = null,
        ?string $redirectSuccess = null,
        ?string $redirectFail = null
    ) {
        $this->callback = $callback;
        $this->return = $return;
        $this->redirectSuccess = $redirectSuccess;
        $this->redirectFail = $redirectFail;
    }

    public function getRequestData(string $paymentType, string $communicationType, string $paymentMethod): array
    {
        $config = ValidationConfig::getUrlsConfig($paymentType, $communicationType, $paymentMethod);

        if (!$config) {
            return [];
        }

        $resultData = [
            "callback" => $this->callback,
        ];

        if (
            (!array_key_exists('ignore', $config) || !in_array('return', $config['ignore']))
            && $this->return !== null
        ) {
            $resultData['return'] = $this->return;
        }

        if (
            (!array_key_exists('ignore', $config) || !in_array('redirectSuccess', $config['ignore']))
            && $this->redirectSuccess
        ) {
            $resultData["redirectSuccess"] = $this->redirectSuccess;
        }

        if ((!array_key_exists('ignore', $config) || !in_array('redirectFail', $config['ignore']))
            && $this->redirectFail
        ) {
            $resultData["redirectFail"] = $this->redirectFail;
        }

        return $resultData;
    }
}
