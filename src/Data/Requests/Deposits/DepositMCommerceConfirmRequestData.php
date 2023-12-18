<?php

namespace Idynsys\BillingSdk\Data\Requests\Deposits;

use Idynsys\BillingSdk\Data\Requests\Auth\AuthenticationTokenInclude;
use Idynsys\BillingSdk\Data\Requests\Auth\WithAuthorizationToken;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Enums\RequestMethod;

class DepositMCommerceConfirmRequestData extends RequestData implements AuthenticationTokenInclude
{
    use WithAuthorizationToken;

    // Метод запроса
    protected string $requestMethod = RequestMethod::METHOD_POST;

    // URL из конфигурации для выполнения запроса
    protected string $urlConfigKeyForRequest = 'DEPOSIT_M_COMMERCE_CONFIRM_URL';

    // ID транзакции
    public string $transactionId;

    // Код подтверждения
    public string $confirmationCode;

    public function __construct(string $transactionId, string $confirmationCode)
    {
        $this->transactionId = $transactionId;
        $this->confirmationCode = $confirmationCode;
    }

    /**
     * Модификация URL запроса
     *
     * @return string
     */
    public function getUrl(): string
    {
        return str_replace('{transaction}', $this->transactionId, parent::getUrl());
    }

    /**
     * Получить данные для запроса
     *
     * @return array
     */
    protected function getRequestData(): array
    {
        return [
            'otp' => $this->confirmationCode
        ];
    }
}