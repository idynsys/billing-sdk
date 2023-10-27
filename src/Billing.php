<?php

namespace Idynsys\BillingSdk;

use GuzzleHttp\Exception\GuzzleException;
use Idynsys\BillingSdk\Data\AuthorisationTokenInclude;
use Idynsys\BillingSdk\Data\AuthRequestData;
use Idynsys\BillingSdk\Data\DepositRequestData;
use Idynsys\BillingSdk\Data\PaymentMethodListRequestData;
use Idynsys\BillingSdk\Data\PayoutRequestData;
use Idynsys\BillingSdk\Data\RequestData;
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

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Получить токен аутентификации в B2B Backoffice
     *
     * @param bool $throwException
     * @return string|null
     */
    public function getToken(bool $throwException = true): ?string
    {
        $data = new AuthRequestData();

        $this->client->sendRequestToSystem($data, $throwException);

        $result = $this->client->getResult('data');
        $this->token = ($result && array_key_exists('data', $result)) ? $result['data'] : null;

        return $this->token;
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

    /**
     * Добавить токен в заголовок запроса
     *
     * @param RequestData $data
     * @return void
     */
    private function addToken(RequestData $data): void
    {
        if ($data instanceof AuthorisationTokenInclude) {
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
    public function getPaymentMethods(): array
    {
        $this->sendRequest(new PaymentMethodListRequestData());

        return $this->client->getResult('items');
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
    public function createDeposit(DepositRequestData $data): array
    {
        $this->sendRequest($data);

        return $this->client->getResult();
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
    public function createPayout(PayoutRequestData $data): array
    {
        $this->sendRequest($data);

        return $this->client->getResult();
    }
}