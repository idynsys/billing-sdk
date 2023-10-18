<?php

namespace Idynsys\BillingSdk;

use Idynsys\BillingSdk\Data\AuthorisationTokenInclude;
use Idynsys\BillingSdk\Data\AuthRequestData;
use Idynsys\BillingSdk\Data\PayInRequestData;
use Idynsys\BillingSdk\Data\PaymentMethodListRequestData;
use Idynsys\BillingSdk\Data\PayoutRequestData;
use Idynsys\BillingSdk\Data\RequestData;
use Idynsys\BillingSdk\Exceptions\UnauthorizedException;

class Billing
{
    private ?string $token = null;

    private int $requestAttempts = 3;

    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getToken(bool $throwException = true): ?string
    {
        $data = new AuthRequestData();

        $this->client->send($data, $throwException);

        $result = $this->client->getResult('data');
        $this->token = ($result && array_key_exists('data', $result)) ? $result['data'] : null;

        return $this->token;
    }

    private function getTokenForRequest(int $attempt = 0): void
    {
        if ($this->token && $attempt === 0) {
            return;
        }

        $attempt++;
        $result = $this->getToken($attempt === $this->requestAttempts);

        if (!$result) {
            if ($attempt < $this->requestAttempts) {
                $this->getTokenForRequest($attempt);
            } else {
                throw new UnauthorizedException();
            }
        }
    }

    private function addToken(RequestData $data): void
    {
        if ($data instanceof AuthorisationTokenInclude) {
            $this->getTokenForRequest();
            $data->setToken($this->token);
        }
    }

    private function sendRequest(RequestData $data): void
    {
        $this->addToken($data);
        $this->client->send($data);
    }

    public function getPaymentMethods(): array
    {
        $this->sendRequest(new PaymentMethodListRequestData());

        return $this->client->getResult('items');
    }

    public function payIn(): array
    {
        $this->sendRequest(new PayInRequestData());

        return $this->client->getResult('items');
    }

    public function payout(): array
    {
        $this->sendRequest(new PayoutRequestData());

        return $this->client->getResult('items');
    }
}