<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Data\UniversalRequestStructures\Traits\SubStructureRequestDataTrait;
use Idynsys\BillingSdk\Enums\CommunicationType;
use Idynsys\BillingSdk\Enums\PaymentMethod;
use Idynsys\BillingSdk\Enums\PaymentType;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

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

    public static function checkIfShouldBe(string $paymentType, string $communicationType, string $paymentMethod): void
    {
        $instance = new self('', '', '');
        $instance->setCurrentConfig($paymentType, $communicationType, $paymentMethod);

        if ($instance->currentConfig !== false) {
            throw new BillingSdkException('Bankcard info must be presented for this deposit', 422);
        }
    }

    protected function setConfig(): void
    {
        $this->config = [
            PaymentType::DEPOSIT => [
                CommunicationType::HOST_2_CLIENT => [
                    PaymentMethod::P2P_NAME => false,
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
        $this->setCurrentConfig($paymentType, $communicationType, $paymentMethod);

        if ($this->currentConfig === false) {
            return;
        }

        if (empty($this->pan) || $this->validatePan()) {
            throw new BillingSdkException('Bankcard info must not be empty and must be correct bankcard number', 422);
        }

        if (empty($this->holderName) || $this->validateCardHolderName()) {
            throw new BillingSdkException('Cardholder name must not be empty and must be correct cardholder name', 422);
        }

        if (empty($this->expiration) || $this->validateExpiration()) {
            throw new BillingSdkException('Expiration date must not be empty and must be correct format', 422);
        }

        if (!$this->inIgnore('cvv') && $this->inOnly('cvv')) {
            if ($this->required('cvv')) {
                $error = empty($this->cvv) || $this->validateCvv();
            } else {
                $error = !empty($this->cvv) && $this->validateCvv();
            }
            if ($error) {
                throw new BillingSdkException('CVV-code has incorrect value', 422);
            }
        }
    }

    private function validatePan(): bool
    {
        $number = str_replace([' ', '-'], '', $this->pan);

        if (!preg_match('/^\d+$/', $number)) {
            return false;
        }

        $length = strlen($number);
        if ($length < 13 || $length > 19) {
            return false;
        }

        // Проверка номера карты по алгоритму Луна
        return $this->luhnCheck($number);
    }

    private function luhnCheck(string $number)
    {
        $sum = 0;
        $shouldDouble = false;

        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $digit = intval($number[$i]);

            if ($shouldDouble) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
            $shouldDouble = !$shouldDouble;
        }

        return ($sum % 10) === 0;
    }

    private function validateCardHolderName(): bool
    {
        $this->holderName = trim($this->holderName);

        if (!preg_match('/^[a-zA-Z\s\-\.\']+$/', $this->holderName)) {
            return false;
        }

        return true;
    }

    private function validateExpiration(): bool
    {
        if (!preg_match('/^(0[1-9]|1[0-2])\/?([0-9]{2})$/', $this->expiration, $matches)) {
            return false;
        }

        // Разбор месяца и года
        //        $month = $matches[1];
        //        $year = $matches[2];

        // Преобразование года в формат 20XX
        //        $currentYear = (int)date('y');
        //        $currentMonth = (int)date('m');
        //
        //        // Преобразуем 2-значный год в 4-значный
        //        $fullYear = $year >= $currentYear ? '20' . $year : '20' . $year;

        // Проверка на то, что срок действия не в прошлом
        //        if ($fullYear < date('Y') || ($fullYear == date('Y') && $month < $currentMonth)) {
        //            return false;
        //        }

        return true;
    }

    private function validateCvv(): bool
    {
        $this->cvv = str_replace([' ', '-'], '', $this->cvv);

        return preg_match('/^\d{3,4}$/', $this->cvv) === 1;
    }
}
