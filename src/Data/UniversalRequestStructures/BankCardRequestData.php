<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Data\UniversalRequestStructures\Traits\SubStructureRequestDataTrait;
use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\PaymentType;

class BankCardRequestData implements RequestDataValidationContract
{
    use SubStructureRequestDataTrait;

    private string $pan;

    private string $holderName;

    private string $expiration;

    private ?string $cvv;

    public function __construct(
        string $pan,
        string $holderName,
        string $expiration,
        ?string $cvv = null
    ) {
        $this->pan = $pan;
        $this->holderName = $holderName;
        $this->expiration = $expiration;
        $this->cvv = $cvv;

        $this->responseProperties = ['pan', 'holderName', 'expiration', 'cvv'];
    }

    public static function checkIfShouldBe(string $paymentMethodName, string $communicationType)
    {
        dump(__METHOD__);
    }

    protected function setConfig(): void
    {
        $this->config = [
            PaymentType::DEPOSIT => [
                CommunicationType::HOST_2_CLIENT => [
                    PaymentMethod::P2P_NAME => [
                        'ignore' => ['cvv']
                    ],
                    PaymentMethod::SBP_NAME => [
                        'ignore' => ['cvv']
                    ],
                    PaymentMethod::SBER_PAY_NAME => [
                        'ignore' => ['cvv']
                    ],
                ],
                CommunicationType::HOST_2_HOST => [
                    PaymentMethod::BANKCARD_NAME => false,
                    PaymentMethod::P2P_NAME => false,
                ]
            ],
        ];;
    }

    public function validate(string $paymentType, string $communicationType, string $paymentMethod): void
    {
        dump(__METHOD__);
    }
}
