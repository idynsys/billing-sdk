<?php

namespace Idynsys\BillingSdk\Contracts;

use Idynsys\BillingSdk\Collections\Collection;
use Idynsys\BillingSdk\Data\Requests\Currencies\PaymentMethodCurrenciesRequestData;
use Idynsys\BillingSdk\Data\Requests\Deposits\DepositMCommerceConfirmRequestData;
use Idynsys\BillingSdk\Data\Requests\Deposits\DepositRequestData;
use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutRequestData;
use Idynsys\BillingSdk\Data\Requests\Transactions\TransactionRequestData;
use Idynsys\BillingSdk\Data\Responses\DepositMCommerceConfirmedResponseData;
use Idynsys\BillingSdk\Data\Responses\DepositResponseData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;
use Idynsys\BillingSdk\Data\Responses\TokenData;
use Idynsys\BillingSdk\Data\Responses\TransactionData;

/**
 * Контракт для класса, взаимодействующего с микросервисом Billing
 */
interface BillingContract
{
    /**
     * Получить токен аутентификации в B2B Backoffice
     *
     * @param bool $throwException
     * @return TokenData
     */
    public function getToken(bool $throwException = true): TokenData;

    /**
     * Получить список доступных платежных методов
     *
     * @return Collection
     */
    public function getPaymentMethods(): Collection;

    /**
     * Создать транзакцию для пополнения счета через Billing в B2B Backoffice
     *
     * @param DepositRequestData $data
     * @return DepositResponseData
     */
    public function createDeposit(DepositRequestData $data): DepositResponseData;

    /**
     * Создать транзакцию для вывода средств со счета через Billing в B2B Backoffice
     *
     * @param PayoutRequestData $data
     * @return PayoutResponseData
     */
    public function createPayout(PayoutRequestData $data): PayoutResponseData;

    /**
     * Получить информацию о транзакции и з биллинга
     *
     * @param TransactionRequestData $requestParams
     * @return TransactionData
     */
    public function getTransactionData(TransactionRequestData $requestParams): TransactionData;

    /**
     * Получить список валют для платежного метода
     *
     * @param PaymentMethodCurrenciesRequestData $requestParams
     * @return Collection
     */
    public function getPaymentMethodCurrencies(PaymentMethodCurrenciesRequestData $requestParams): Collection;

    /**
     * Подтверждение транзакции через код для депозита по платежному методу M-Commerce
     *
     * @param DepositMCommerceConfirmRequestData $requestParams
     * @return DepositMCommerceConfirmedResponseData
     */
    public function confirmMCommerceDeposit(DepositMCommerceConfirmRequestData $requestParams
    ): DepositMCommerceConfirmedResponseData;
}