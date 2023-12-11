<?php

namespace Idynsys\BillingSdk;

use GuzzleHttp\Exception\GuzzleException;
use Idynsys\BillingSdk\Collections\Collection;
use Idynsys\BillingSdk\Collections\PaymentMethodsCollection;
use Idynsys\BillingSdk\Data\Requests\Auth\AuthenticationTokenInclude;
use Idynsys\BillingSdk\Data\Requests\Auth\AuthRequestData;
use Idynsys\BillingSdk\Data\Requests\Deposits\DepositRequestData;
use Idynsys\BillingSdk\Data\Requests\PaymentMethods\PaymentMethodListRequestData;
use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutRequestData;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Data\Requests\Transactions\TransactionRequestData;
use Idynsys\BillingSdk\Data\Responses\DepositResponseData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;
use Idynsys\BillingSdk\Data\Responses\TokenData;
use Idynsys\BillingSdk\Data\Responses\TransactionData;
use Idynsys\BillingSdk\Exceptions\AnotherException;
use Idynsys\BillingSdk\Exceptions\AuthException;
use Idynsys\BillingSdk\Exceptions\MethodException;
use Idynsys\BillingSdk\Exceptions\NotFoundException;
use Idynsys\BillingSdk\Exceptions\UnauthorizedException;
use Idynsys\BillingSdk\Exceptions\UrlException;

/**
 * Класс для выполнения запросов к сервису Billing в B2B Backoffice
 */
final class Billing
{
    // Сохраняет токен для выполнения операций по счету
    private ?string $token = null;

    // Количество попыток для запроса токена аутентификации
    private int $requestAttempts = 3;

    // Объект-клиент, через который выполняется запрос и обрабатывается результат
    private Client $client;

    public function __construct(?string $clientId = null, ?string $clientSecret = null)
    {
        $this->client = new Client();

        if ($clientId) {
            Config::set('clientId', $clientId);
        }

        if ($clientSecret) {
            Config::set('clientSecret', $clientSecret);
        }
    }

    /**
     * Получить токен аутентификации в B2B Backoffice
     *
     * @param bool $throwException
     * @return string|null
     */
    public function getToken(bool $throwException = true): TokenData
    {
        $data = new AuthRequestData();

        $this->client->sendRequestToSystem($data, $throwException);

        $result = $this->client->getResult('data');
        $this->token = ($result && array_key_exists('data', $result)) ? $result['data'] : '';

        return new TokenData($this->token);
    }

    /**
     * Получить токен аутентификации для выполнения запросов к сервису Billing
     *
     * @param int $attempt
     * @return void
     */
    private function getTokenForRequest(int $attempt = 0): void
    {
        if ($this->token && $attempt === 0) {
            return;
        }

        if (++$attempt <= $this->requestAttempts) {
            $result = $this->getToken($attempt === $this->requestAttempts);

            if (!$result) {
                $this->getTokenForRequest($attempt);
            }
        } else {
            $result = false;
        }

        if (!$result) {
            throw new UnauthorizedException();
        }
    }

    /**
     * Добавить токен в заголовок запроса
     *
     * @param RequestData $data
     * @return void
     */
    private function addToken(RequestData $data): void
    {
        if ($data instanceof AuthenticationTokenInclude) {
            $this->getTokenForRequest();
            $data->setToken($this->token);
        }
    }

    /**
     * Отправить запрос в B2B Backoffice
     *
     * @param RequestData $data
     * @return void
     * @throws AnotherException
     * @throws AuthException
     * @throws GuzzleException
     * @throws MethodException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UrlException
     */
    private function sendRequest(RequestData $data): void
    {
        $this->addToken($data);
        $this->client->sendRequestToSystem($data);
    }

    /**
     * Получить список доступных платежных методов
     *
     * @return array
     * @throws AnotherException
     * @throws AuthException
     * @throws GuzzleException
     * @throws MethodException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UrlException
     */
    public function getPaymentMethods(): Collection
    {
        $this->sendRequest(new PaymentMethodListRequestData());
        $collection = new PaymentMethodsCollection();
        $collection->addItems($this->client->getResult('items'), 'items');

        return $collection;
    }

    /**
     * Создать транзакцию для пополнения счета через Billing в B2B Backoffice
     *
     * @param DepositRequestData $data
     * @return array
     * @throws AnotherException
     * @throws AuthException
     * @throws GuzzleException
     * @throws MethodException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UrlException
     */
    public function createDeposit(DepositRequestData $data): DepositResponseData
    {
        $this->sendRequest($data);

        return DepositResponseData::from($this->client->getResult());
    }

    /**
     * Создать транзакцию для вывода средств со счета через Billing в B2B Backoffice
     *
     * @param PayoutRequestData $data
     * @return array
     * @throws AnotherException
     * @throws AuthException
     * @throws GuzzleException
     * @throws MethodException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws UrlException
     */
    public function createPayout(PayoutRequestData $data): PayoutResponseData
    {
        $this->sendRequest($data);

        return PayoutResponseData::from($this->client->getResult());
    }

    public function getTransactionData(TransactionRequestData $requestParams)
    {
        $this->sendRequest($requestParams);

        return TransactionData::from($this->client->getResult());
    }
}