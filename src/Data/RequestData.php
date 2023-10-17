<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Enums\RequestMethod;

abstract class RequestData
{
    protected string $requestMethod;

    public function __construct(string $requestMethod = RequestMethod::METHOD_POST)
    {
        $this->requestMethod = $requestMethod;
    }

    abstract public function getUrl(): string;

    abstract protected function getRequestData(): array;

    public function getMethod(): string
    {
        return $this->requestMethod;
    }

    public function getData(): array
    {
        $paramsType = $this->requestMethod === RequestMethod::METHOD_POST ? 'form_params' : 'query';

        return [
            'headers'  => $this->getHeadersData(),
            $paramsType => $this->getRequestData()
        ];
    }

    protected function getSecretApplicationKey(): string
    {
        return getenv('BILLING_SDK_APPLICATION_SECRET_KEY') ?: '';
    }

    protected function getHeadersData(): array
    {
        $headers = [
            'X-Authorization-Sign' => hash_hmac(
                'sha512',
                json_encode($this->getRequestData()),
                $this->getSecretApplicationKey()
            )
        ];

        if ($this->requestMethod === RequestMethod::METHOD_POST) {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        return $headers;
    }

}