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

В каталоге Вашего проекта, где расположен файл composer.json,  выполните команду:
```
composer require idynsys/billing-sdk
```

## Использование

1. Создать экземпляр класса 

```php
<?php

use Idynsys\BillingSdk\Billing;
...

$billing = new Billing();
```

2.1. Получить список доступных платежных методов
```php
<?php

$result = $billing->getPaymentMethods();
```

2.2. Создать транзакцию для пополнения счета
```php
<?php

use Idynsys\BillingSdk\Data\DepositRequestData;

// Созадть DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositRequestData(
    $paymentMethodId,           // идентификатор платежного метода из списка доступных платежных методов
    $paymentMethodName,         // наименование платежного метода из списка доступных платежных методов
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого созадется транзакция
    $customerEmail,             // email пользователя, совершающего операцию
    $amount,                    // сумма попоплнения
    $currencyCode               // валюта суммы пополнения
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
  "currency"            => "string"
]
```

2.3. Создать транзакцию для вывода денежных средств со счета
```php
<?php

use Idynsys\BillingSdk\Data\PayoutRequestData;

// Созадть DTO для запроса на создание транзакции для вывода средств со счета
$requestParams = new PayoutRequestData(
    $paymentMethodId,   // идентификатор платежного метода из списка доступных платежных методов
    $paymentMethodName, // наименование платежного метода из списка доступных платежных методов
    $amount,            // сумма попоплнения
    $currencyCode,      // валюта суммы пополнения
    $cardNumber,        // Номер банковской карты, на которую выводятся деньги
    $cardExpiration,    // Месяц и год оконччания действия карты (как написано на карте)
    $cardRecipientInfo  // Данные владельца карты (Имя Фамилия, как написано на карте)
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