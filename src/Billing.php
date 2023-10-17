<?php

namespace Idynsys\BillingSdk;

use Idynsys\BillingSdk\Data\AuthRequestData;
use Idynsys\BillingSdk\Data\PaymentMethodListRequestData;
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

        $result = $this->client->getResult('access_token');
        $this->token = ($result && array_key_exists('access_token', $result)) ? $result['access_token'] : null;

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

    public function getPaymentMethods(): array
    {
        $this->getTokenForRequest();

        $data = new PaymentMethodListRequestData($this->token);

        $this->client->send($data);

        return $this->client->getResult('items');
    }
}