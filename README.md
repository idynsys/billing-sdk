# Billing SDK

## Общее описание

Пакет предназначен для интеграции внешней системы на PHP 7.4+ и B2B backoffice сервиса Billing 
для создания транзакций в платежных системах, зарегистрированных в B2B backoffice.Billing.

Пакет позволяет выполнять следующие действия:
- Получить список доступных платежных методов
- Создавать транзакцию для пополнения денежных средств на счете. 
- Создавать транзакцию для вывода денежных средств со счета.

Эти действия только инициируют начало действий со счетом, но не выполняют эти операции полностью от начала до конца, 
т.е. после создания данных для проведения операции необходимо будет выполнить дополнительные действия, описанные в 
документации по работе с сервисом Billing системы B2B Backoffice.

## Требования

Приложение, на которое устанавливается пакет должна иметь:

- PHP 7.4 или выше
- ext-openssl - установленное расширение для php
- ext-json - установленное расширение для php

## Установка

1. В каталоге Вашего проекта, где расположен файл composer.json,  выполните команду:
```
composer require idynsys/billing-sdk
```
2. Настройка Вашего приложения для выполнения запроса к B2B Backoffice.<br>  
    Для выполнения запроса необходимо в запросах передавать информацию об идентификаторе
    приложения с использованием секретного ключа для подписи параметров запрос. Это 
    можно сделать двумя способами.<br>  
    2.1. Через переменные окружения:<br>  
    В переменных окружения приложения, где устанавливается этот пакет, необходимо создать 
    переменные окружения:
    ```dotenv
    BILLING_SDK_CLIENT_ID=<clientId>
    BILLING_SDK_APPLICATION_SECRET_KEY=<secret>
    ```
   <br>  

    2.2. Через создание объекта от класса Billing:
    ```php
    $billing = new \Idynsys\BillingSdk\Billing('<clientId>', '<secret>');
    ```
    
    где "clientId" и "secret" будут переданы Вашей компании после регистрации внешнего 
    приложения в B2B Backoffice для возможности выполнения запросов через B2B.

<br>
3. !!! Для версии на Production необходимо установить переменную окружения:

```dotenv
BILLING_SDK_MODE=PRODUCTION
```
Если эта переменная не установлена или имеет другое значение, то все запросы
будут перенаправляться на тестовый сервер B2B Backoffice.

## Использование

### Создать экземпляр класса Billing: 

```php
<?php

use Idynsys\BillingSdk\Billing;
...

// Если "clientId" и "secret" установлены через переменные окружения (см. п.2.1.)
$billing = new Billing();
...

// или через прямое указание через параметры (см. п.2.2.)
$billing = new Billing('applicationName', 'applicationSecret');
...
```
### Описание методов класса Billing:

#### Получить список доступных платежных методов
```php
<?php

use Idynsys\BillingSdk\Collections\PaymentMethodsCollection;

/** @var PaymentMethodsCollection $result */
$result = $billing->getPaymentMethods();
```
Ответ (response) для данного запроса будет объект класса
_\Idynsys\BillingSdk\Collections\PaymentMethodsCollection_. Этот класс реализует интерфейс _Iterator_.
Элементами этой коллекции будут объекты класса _\Idynsys\BillingSdk\Data\Entities\PaymentMethodData_.
```php
// получить список объектов коллекции

$result->all();
```

#### Создать транзакцию для пополнения счета
1. _Создание транзакции для платежного метода P2P_

```php
<?php

use Idynsys\BillingSdk\Data\Requests\Deposits\DepositRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositRequestData(
    $paymentMethodId,           // идентификатор платежного метода из списка доступных платежных методов
    $paymentMethodName,         // наименование платежного метода из списка доступных платежных методов
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $customerEmail,             // email пользователя, совершающего операцию
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $callbackUrl                // URL для передачи результата создания транзакции в B2B backoffice
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```
Если операция выполнена успешно, то ответ придет в формате ассоциативного массива:
```php
[
  "payment_status"      => "SUCCESS",
  "payment_type"        => "PAYMENT_PAGE",
  "redirect_url"        => "string",
  "payment_id"          => "string",
  "destination_card"    => "string",
  "amount"              => "string",
  "currency"            => "string",
]
```

2.3. Создать транзакцию для вывода денежных средств со счета

```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutRequestData;

// Созадть DTO для запроса на создание транзакции для вывода средств со счета
$requestParams = new PayoutRequestData(
    $paymentMethodId,   // идентификатор платежного метода из списка доступных платежных методов
    $paymentMethodName, // наименование платежного метода из списка доступных платежных методов
    $amount,            // сумма попоплнения
    $currencyCode,      // валюта суммы пополнения
    $cardNumber,        // Номер банковской карты, на которую выводятся деньги
    $cardExpiration,    // Месяц и год оконччания действия карты (как написано на карте)
    $cardRecipientInfo, // Данные владельца карты (Имя Фамилия, как написано на карте)
    $callbackUrl        // URL для передачи результата создания транзакции в B2B backoffice
);

// Создать транзакцию и получить результат
$result = $billing->createPayout($requestParams);
```
Если операция выполнена успешно, то ответ придет в формате ассоциативного массива:
```php
[
  "transactionId": "идентификатор транзакции"
]
```
3. Обработка исключительных ситуаций
При запросе к системе могут возникнуть ошибки, связанные с некорректно отправленными данными
или невозможностью выполнить операцию. Обработать ошибки можно следующим образом:
```php
<?php
use Idynsys\BillingSdk\Exceptions\RequestException;
use Throwable;

try {
    // выполнить запрос из описанных в п. 2.
} catch (RequestException $exception) {
    $requestError = $exception->getOriginalMessage();
    $requestCode = $exception->getCode());
    // обработать ошибку
} catch (Throwable $exception) {
    // обработать ошибку
}
```