<?php

namespace Idynsys\BillingSdk;

use Idynsys\BillingSdk\Collections\Collection;
use Idynsys\BillingSdk\Collections\PaymentMethodCurrenciesCollection;
use Idynsys\BillingSdk\Collections\PaymentMethodsCollection;
use Idynsys\BillingSdk\Config\ConfigContract;
use Idynsys\BillingSdk\Contracts\BillingContract;
use Idynsys\BillingSdk\Data\Requests\Currencies\PaymentMethodCurrenciesRequestData;
use Idynsys\BillingSdk\Data\Requests\Deposits\DepositMCommerceConfirmRequestData;
use Idynsys\BillingSdk\Data\Requests\Deposits\DepositRequestData;
use Idynsys\BillingSdk\Data\Requests\PaymentMethods\PaymentMethodListRequestData;
use Idynsys\BillingSdk\Data\Requests\PaymentMethods\v2\PaymentMethodListRequestData as NewPaymentMethodListRequestData;
use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutRequestData;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Data\Requests\Transactions\TransactionRequestData;
use Idynsys\BillingSdk\Data\Responses\DepositMCommerceConfirmedResponseData;
use Idynsys\BillingSdk\Data\Responses\DepositResponseData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;
use Idynsys\BillingSdk\Data\Responses\TransactionData;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;

/**
 * Класс для выполнения запросов к сервису Billing в B2B Backoffice
 */
final class Billing implements BillingContract
{
    // Объект-клиент, через который выполняется запрос и обрабатывается результат
    private Client $client;

    private ConfigContract $config;

    public function __construct(?string $clientId = null, ?string $clientSecret = null, ?ConfigContract $config = null)
    {
        $this->client = new Client();
        $this->config = $config ?: Config::getInstance();

        if ($clientId) {
            $this->config->set('clientId', $clientId);
        }

        if ($clientSecret) {
            $this->config->set('clientSecret', $clientSecret);
        }
    }

    /**
     *  Отправить запрос в B2B Backoffice
     *
     * @param RequestData $data
     * @return void
     * @throws BillingSdkException
     */
    private function sendRequest(RequestData $data): void
    {
        $this->client->sendRequestToSystem($data);
    }

    /**
     * Получить список доступных платежных методов
     *
     * @return Collection
     * @throws BillingSdkException
     * @throws \JsonException
     */
    public function getPaymentMethods(
        null|NewPaymentMethodListRequestData|PaymentMethodListRequestData $requestData = null
    ): Collection {
        if ($requestData === null) {
            $requestData = new PaymentMethodListRequestData();
        }

        $this->sendRequest($requestData);
        $collection = new PaymentMethodsCollection();
        $collection->addItems($this->client->getResult('items'), 'items');

        return $collection;
    }

    /**
     * Создать транзакцию для пополнения счета через Billing в B2B Backoffice
     *
     * @param DepositRequestData $data
     * @return DepositResponseData
     * @throws BillingSdkException
     * @throws \JsonException
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
     * @return PayoutResponseData
     * @throws BillingSdkException
     * @throws \JsonException
     */
    public function createPayout(PayoutRequestData $data): PayoutResponseData
    {
        $this->sendRequest($data);

        return PayoutResponseData::from($this->client->getResult());
    }

    /**
     * Получить информацию о транзакции и з биллинга
     *
     * @param TransactionRequestData $requestParams
     * @return TransactionData
     * @throws BillingSdkException
     * @throws \JsonException
     */
    public function getTransactionData(TransactionRequestData $requestParams): TransactionData
    {
        $this->sendRequest($requestParams);

        return TransactionData::from($this->client->getResult());
    }

    /**
     * Получить список валют для платежного метода
     *
     * @param PaymentMethodCurrenciesRequestData $requestParams
     * @return Collection
     * @throws BillingSdkException
     * @throws \JsonException
     */
    public function getPaymentMethodCurrencies(PaymentMethodCurrenciesRequestData $requestParams): Collection
    {
        $this->sendRequest($requestParams);
        $collection = new PaymentMethodCurrenciesCollection();

        $collection->addItems($this->client->getResult('items'), 'items');

        return $collection;
    }

    /**
     * Подтверждение транзакции через код для депозита по платежному методу M-Commerce
     *
     * @param DepositMCommerceConfirmRequestData $requestParams
     * @return DepositMCommerceConfirmedResponseData
     * @throws BillingSdkException
     * @throws \JsonException
     */
    public function confirmMCommerceDeposit(
        DepositMCommerceConfirmRequestData $requestParams
    ): DepositMCommerceConfirmedResponseData {
        $this->sendRequest($requestParams);

        return DepositMCommerceConfirmedResponseData::from($this->client->getResult());
    }
}
