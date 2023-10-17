<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Config\Config;

use Idynsys\BillingSdk\Enums\RequestMethod;

class PaymentMethodListRequestData extends RequestData
{

    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;

        parent::__construct(RequestMethod::METHOD_GET);
    }

    public function getUrl(): string
    {
        return getenv(
            'BILLING_SDK_MODE'
        ) === 'PRODUCTION' ? Config::PROD_PAYMENT_METHODS_URL : Config::PREPROD_PAYMENT_METHODS_URL;
    }

/*    public function getData(): array
    {
        return [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token
            ]
        ];
    }*/

    protected function getRequestData(): array
    {
        return [];
    }
}