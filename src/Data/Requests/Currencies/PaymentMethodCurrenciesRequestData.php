<?php

namespace Idynsys\BillingSdk\Data\Requests\Currencies;

use Idynsys\BillingSdk\Data\Requests\Auth\AuthenticationTokenInclude;
use Idynsys\BillingSdk\Data\Requests\Auth\WithAuthorizationToken;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\RequestMethod;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

class PaymentMethodCurrenciesRequestData extends RequestData implements AuthenticationTokenInclude
{
    use WithAuthorizationToken;

    protected string $requestMethod = RequestMethod::METHOD_GET;

    protected string $urlConfigKeyForRequest = 'PAYMENT_METHOD_CURRENCIES_URL';

    public string $paymentMethodName;

    public function __construct(string $methodName)
    {
        $this->paymentMethodName = $methodName;

        $this->validate();
    }

    protected function validate(): void
    {
        if (!in_array(
            $this->paymentMethodName,
            [PaymentMethod::P2P_NAME, PaymentMethod::BANKCARD_NAME, PaymentMethod::M_COMMERCE_NAME]
        )) {
            throw new BillingSdkException(
                'Method name should have value of ' . PaymentMethod::P2P_NAME
                . ', ' . PaymentMethod::BANKCARD_NAME . ' or ' . PaymentMethod::M_COMMERCE_NAME, 422
            );
        };
    }

    protected function getRequestData(): array
    {
        return [
            'payment-method' => $this->paymentMethodName
        ];
    }
}