<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

use Idynsys\BillingSdk\Exceptions\BillingSdkException;

class SessionDetailsRequestData implements RequestDataValidationContract
{
    private string $acceptLanguage;

    private string $fingerprint;

    private string $ipAddress;

    private string $userAgent;
    private ?string $userLanguage;

    public function __construct(
        string $fingerprint,
        string $ipAddress,
        string $userAgent,
        string $acceptLanguage,
        ?string $userLanguage = null
    ) {
        $this->fingerprint = $fingerprint;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->acceptLanguage = $acceptLanguage;
        $this->userLanguage = $userLanguage;
    }

    public function getRequestData(): array
    {
        $resultData = [
            'fingerprint' => $this->fingerprint,
            'ipAddress' => $this->ipAddress,
            'userAgent' => $this->userAgent,
            'acceptLanguage' => $this->acceptLanguage
        ];

        if ($this->userLanguage !== null) {
            $resultData['userLanguage'] = $this->userLanguage;
        }

        return $resultData;
    }

    public function validate(string $paymentType, string $communicationType, string $paymentMethod): void
    {
        if (empty($this->fingerprint)) {
            throw new BillingSdkException('Fingerprint value can not be empty', 422);
        }

        if (filter_var($this->ipAddress, FILTER_VALIDATE_IP) === false) {
            throw new BillingSdkException('Invalid IP address', 422);
        }

        if (empty($this->acceptLanguage)) {
            throw new BillingSdkException('Invalid acceptLanguage format', 422);
        }

        if (empty($this->userAgent)) {
            throw new BillingSdkException('User-Agent cannot be empty', 422);
        }

        if ($this->userLanguage !== null && !preg_match('/^[a-z]{3}$/', $this->userLanguage)) {
            throw new BillingSdkException('Invalid userLanguage format, must be 3-letter code', 422);
        }
    }
}
