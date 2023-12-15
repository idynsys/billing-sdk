# Billing SDK

## Общее описание

Пакет предназначен для интеграции внешней системы на PHP 7.4+ и B2B backoffice сервиса Billing 
для создания транзакций в платежных системах, зарегистрированных в B2B backoffice.Billing.

Пакет позволяет выполнять следующие действия:
- Получить список доступных платежных методов
- Создавать транзакцию для пополнения денежных средств на счете. 
- Создавать транзакцию для вывода денежных средств со счета.
- Получить информацию о транзакции.

Действия для пополнения и вывода средтсв только инициируют начало действий со 
счетом, но не выполняют эти операции полностью от начала до конца, т.е. после 
создания данных для проведения операции необходимо будет выполнить дополнительные
действия, описанные в документации по работе с сервисом Billing системы 
B2B Backoffice.

## Требования

Приложение, на которое устанавливается пакет должна иметь:

- PHP 7.4 или выше
- ext-openssl - установленное расширение для php
- ext-json - установленное расширение для php

## Установка

1. В каталоге Вашего проекта, где расположен файл composer.json, выполните команду:
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
$billing = new Billing('clientId', 'secret');
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

#### Получить список валют для платежных методов

```php
<?php

use Idynsys\BillingSdk\Data\Requests\Currencies\PaymentMethodCurrenciesRequestData;
use Idynsys\BillingSdk\Collections\PaymentMethodCurrenciesCollection;

/* Значение метода - одна из 3х констант: 
     Idynsys\BillingSdk\Enums\PaymentMethod::P2P_NAME
     Idynsys\BillingSdk\Enums\PaymentMethod::BANKCARD_NAME
     Idynsys\BillingSdk\Enums\PaymentMethod::M_COMMERCE_NAME
*/
$paymentMethodName = \Idynsys\BillingSdk\Enums\PaymentMethod::P2P_NAME;
$requestParams = new PaymentMethodCurrenciesRequestData($paymentMethodName);

/** @var PaymentMethodCurrenciesCollection $result */
$result = $billing->getPaymentMethodCurrencies($requestParams);
```
Ответ (response) для данного запроса будет объект класса
_\Idynsys\BillingSdk\Collections\PaymentMethodCurrenciesCollection_.
Этот класс реализует интерфейс _Iterator_. Элементами этой коллекции будут объекты
класса _\Idynsys\BillingSdk\Data\Entities\CurrencyData_.
```php
// получить список объектов коллекции

$result->all();
```

#### Создать транзакцию для пополнения счета

Есть три метода, позволяющие пополнение счета:
- через платежный метод p2p,
- через платежный метод Bankcard,
- через платежный метод Mobile Commerce.

1. _Создание транзакции депозита через платежный метод P2P_

```php
<?php

use Idynsys\BillingSdk\Data\Requests\Deposits\DepositP2PRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositP2PRequestData(
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

2. _Создание транзакции депозита через платежный метод Bankcard_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Deposits\DepositBankcardRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositBankcardRequestData(
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

3. _Создание транзакции депозита через платежный метод Mobile Commerce_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Deposits\DepositMCommerceRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositMCommerceRequestData(
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

4. _Response_

Если транзакция депозита была создана успешно, то ответом (response) 
будет объект класса \Idynsys\BillingSdk\Data\Responses\DepositResponseData:
```
Idynsys\BillingSdk\Data\Responses\DepositResponseData
  +paymentStatus: "SUCCESS"
  +transactionId: "a45da91c-536d-4019-8c6c-1e822f417507"
  +amount: 4325.0
  +currency: "KZT"
  +redirectUrl: null
  +card: Idynsys\BillingSdk\Data\Responses\BankCardData
    +cardNumber: "6666 6666 6666 6666 66"
    +bankName: "Kaspi"
    +lifetimeInMinutes: 8
  }
  +destinationCard: null
}
```

#### Создать транзакцию для вывода денежных средств со счета

Есть два метода, позволяющие создать транзакцию для вывода средства со счета:
- через платежный метод p2p,
- через платежный метод Bankcard.

1. _Создание транзакции для вывода средств со счета через метод p2p_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutP2PRequestData;

// Создать DTO для запроса на создание транзакции для вывода средств со счета
$requestParams = new PayoutP2PRequestData(
    $amount,            // сумма ввода
    $currencyCode,      // валюта суммы вывода
    $cardNumber,        // Номер банковской карты, на которую выводятся деньги
    $cardExpiration,    // Месяц и год окончания действия карты (как написано на карте)
    $cardRecipientInfo, // Данные владельца карты (Имя Фамилия, как написано на карте)
    $callbackUrl        // URL для передачи результата создания транзакции в B2B backoffice
);

// Создать транзакцию и получить результат
$result = $billing->createPayout($requestParams);
```

2. _Создание транзакции для вывода средств со счета через метод Bankcard_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutBankcardRequestData;

// Создать DTO для запроса на создание транзакции для вывода средств со счета
$requestParams = new PayoutP2PRequestData(
    $amount,            // сумма ввода
    $currencyCode,      // валюта суммы вывода
    $cardNumber,        // Номер банковской карты, на которую выводятся деньги
    $cardExpiration,    // Месяц и год окончания действия карты (как написано на карте)
    $cardRecipientInfo, // Данные владельца карты (Имя Фамилия, как написано на карте)
    $callbackUrl        // URL для передачи результата создания транзакции в B2B backoffice
);

// Создать транзакцию и получить результат
$result = $billing->createPayout($requestParams);
```

3. _Response_

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