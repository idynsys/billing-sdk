<?php

namespace Idynsys\BillingSdk\Data\UniversalRequestStructures;

class SessionDetailsRequestData
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
}
