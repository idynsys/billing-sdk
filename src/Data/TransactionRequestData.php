<?php

namespace Idynsys\BillingSdk\Data;

use Idynsys\BillingSdk\Enums\RequestMethod;

class TransactionRequestData extends RequestData implements AuthenticationTokenInclude
{
    use WithAuthorizationToken;

    protected string $requestMethod = RequestMethod::METHOD_GET;
    public string $transactionId;
    protected string $urlConfigKeyForRequest = 'TRANSACTION_DATA_URL';

    public function __construct(string $transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public function getUrl(): string
    {
        return parent::getUrl() . '/' . $this->transactionId;
    }
}