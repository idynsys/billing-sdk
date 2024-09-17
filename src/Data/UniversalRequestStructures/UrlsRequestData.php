<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Data\UniversalRequestStructures\Traits\SubStructureRequestDataTrait;
use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\PaymentType;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

class UrlsRequestData implements RequestDataValidationContract
{
    use SubStructureRequestDataTrait;

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

        $this->responseProperties = ['callback', 'return', 'redirectSuccess', 'redirectFail'];
    }

    protected function setConfig(): void
    {
        $this->config = [
            PaymentType::DEPOSIT => [
                CommunicationType::HOST_2_CLIENT => [
                    PaymentMethod::P2P_NAME => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::SBP_NAME => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                    PaymentMethod::SBER_PAY_NAME => [
                        'required' => ['callback', 'return', 'redirectSuccess', 'redirectFail']
                    ],
                ],
                CommunicationType::HOST_2_HOST => [
                    PaymentMethod::BANKCARD_NAME => [
                        'ignore' => ['return', 'redirectSuccess', 'redirectFail'],
                    ],
                    PaymentMethod::P2P_NAME => [
                        'ignore' => ['return', 'redirectSuccess', 'redirectFail'],
                    ],
                ]
            ],
        ];
    }

    public function validate(string $paymentType, string $communicationType, string $paymentMethod): void
    {
        $this->setCurrentConfig($paymentType, $communicationType, $paymentMethod);

        if (empty($this->callback) || !$this->isUrl($this->callback)) {
            throw new BillingSdkException('Callback url must not be correct url address', 422);
        }

        $this->validateUrlProperty('return');
        $this->validateUrlProperty('redirectSuccess');
        $this->validateUrlProperty('redirectFail');
    }

    private function validateUrlProperty(string $propertyName): void
    {
        if (!$this->inIgnore($propertyName) && $this->inOnly($propertyName)) {
            if ($this->required($propertyName)) {
                $error = empty($this->{$propertyName}) || !$this->isUrl($this->{$propertyName});
            } else {
                $error = !empty($this->{$propertyName}) && !$this->isUrl($this->{$propertyName});
            }
            if ($error) {
                throw new BillingSdkException(ucfirst($propertyName) . ' url must not be correct url address', 422);
            }
        }
    }

    private function isUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

}
