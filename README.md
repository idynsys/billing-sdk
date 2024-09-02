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

В классе DTO есть параметр "trafficType". Этот параметр необязательный и может принимать следующие значения:
- ftd - первичный трафик (для непроверенных пользователей делающих оплату первый раз)
- trusted - вторичный трафик (для доверенных пользователей)

```php
<?php

use Idynsys\BillingSdk\Collections\PaymentMethodsCollection;
use Idynsys\BillingSdk\Data\Requests\PaymentMethods\v2\PaymentMethodListRequestData;

$requestParameters = new PaymentMethodListRequestData(
    $amount,        // сумма, по которой вбираются доступные платежные методы
    $currency,      // валюта, по которой выбираются доступные платежные методы
    $paymentType,   // тип платежа, доступные значения - deposit, withdrawal
    $trafficType    // Тип трафика для выполнения транзакции в платёжной системе
);

/** @var PaymentMethodsCollection $result */
$result = $billing->getPaymentMethods($requestParameters);
```
Ответ (response) для данного запроса будет объект класса
_\Idynsys\BillingSdk\Collections\PaymentMethodsCollection_. Этот класс реализует интерфейс _Iterator_.
Элементами этой коллекции будут объекты класса _\Idynsys\BillingSdk\Data\Entities\PaymentMethodData_.
```php
// получить список объектов коллекции

$result->all();
```

#### Получить список валют для платежных методов

В классе DTO есть параметр "trafficType". Этот параметр необязательный и может принимать следующие значения:
- ftd - первичный трафик (для непроверенных пользователей делающих оплату первый раз)
- trusted - вторичный трафик (для доверенных пользователей)

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
$requestParams = new PaymentMethodCurrenciesRequestData(
    $paymentMethodName, // наименование платежного метода
    $amount,            // сумма, для которой ищется платежный метод
    $paymentType,       // тип платежа, доступные значения - deposit, withdrawal
    $trafficType        // Тип трафика для выполнения транзакции в платёжной системе
);

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

В каждом классе DTO есть параметр "trafficType". Этот параметр необязательный и может принимать следующие значения:
- ftd - первичный трафик (для непроверенных пользователей делающих оплату первый раз), значение по умолчанию
- trusted - вторичный трафик (для доверенных пользователей)

I. Реализованные методы для пополнения счета (Deposits)

| №№ | Вид <br/>взаимодействия | Платежный метод | Класс DTO                                                                                                                      |
|----|-------------------------|-----------------|--------------------------------------------------------------------------------------------------------------------------------|
| 1  | Host2Host               | p2p             | \Idynsys\BillingSdk\Data\Requests\Deposits\v2\DepositP2PRequestData [см.](#deposit-h2h-p2p)                                    |
| 2  | Host2Client             | p2p             | \Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositP2PHost2ClientRequestData [см.](#deposit-h2с-p2p)                |
| 3  | Host2Host               | Bankcard        | \Idynsys\BillingSdk\Data\Requests\Deposits\v2\DepositBankcardRequestData [см.](#deposit-h2h-bankcard)                          |
| 4  | Host2Host               | Mobile Commerce | \Idynsys\BillingSdk\Data\Requests\Deposits\v2\DepositMCommerceRequestData [см.](#deposit-h2h-m-commerce)                       |
| 5  | Host2Client             | SberPay         | \Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositSberPay2PHostClientRequestData [см.](#deposit-h2c-sber-pay)      |
| 6  | Host2Client             | SBP             | \Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositSbpHost2ClientRequestData [см.](#deposit-h2c-sbp)                |
| 7  | Host2Client             | Havale          | \Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositHavaleHostToClientRequestData [см.](#deposit-h2c-havale)         |
| 8  | Host2Client             | HayHay          | \Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositHayHayHostToClientRequestData [см.](#deposit-h2c-hay-hay)        |
| 9  | Host2Client             | eManat          | \Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositHostEManat2ClientRequestData [см.](#deposit-h2c-emanat)          |
| 10 | Host2Client             | InCardP2P       | \Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositInCardP2PHostToClientRequestData [см.](#deposit-h2c-in-card-p2p) |
| 11 | Host2Client             | M10             | \Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositM10HostToClientRequestData [см.](#deposit-h2c-m10)               |
| 12 | Host2Client             | Papara          | \Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositPaparaHostToClientRequestData [см.](#deposit-h2c-papara)         |
| 13 | Host2Client             | PayCo           | \Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositPayCoHostToClientRequestData [см.](#deposit-h2c-pey-co)          |
| 14 | Host2Client             | Payfix          | \Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositPayfixHostToClientRequestData [см.](#deposit-h2c-pay-fix)        |
| 15 | Host2Client             | Pep             | \Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositPepHostToClientRequestData [см.](#deposit-h2c-pep)               |
| 16 | Host2Client             | SmartCard       | \Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositSmartCardHostToClientRequestData [см.](#deposit-h2c-smart-card)  |

<a id="deposit-h2h-p2p"></a>
1. _Создание транзакции депозита через платежный метод P2P Host2Host_

```php
<?php

use Idynsys\BillingSdk\Data\Requests\Deposits\v2\DepositP2PRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositP2PRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $callbackUrl,               // URL для передачи результата создания транзакции в B2B backoffice
    $customerEmail,             // email пользователя, совершающего операцию
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```
<a id="deposit-h2с-p2p"></a>
2. _Создание транзакции депозита через платежный метод P2P Host2Client_

```php
<?php
use Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositP2PHost2ClientRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositP2PHost2ClientRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $customerId,                // ID пользователя, совершающего операцию
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $callbackUrl,               // URL для передачи результата создания транзакции в B2B backoffice
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

<a id="deposit-h2h-bankcard"></a>
3. _Создание транзакции депозита через платежный метод Bankcard Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Deposits\v2\DepositBankcardRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositBankcardRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $callbackUrl,               // URL для передачи результата создания транзакции в B2B backoffice
    $customerEmail,             // email пользователя, совершающего операцию
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

<a id="deposit-h2h-m-commerce"></a>
4. _Создание транзакции депозита через платежный метод Mobile Commerce Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Deposits\v2\DepositMCommerceRequestData;
use Idynsys\BillingSdk\Data\Responses\DepositResponseData;
use Idynsys\BillingSdk\Data\Requests\Deposits\DepositMCommerceConfirmRequestData;
use Idynsys\BillingSdk\Data\Responses\DepositMCommerceConfirmedResponseData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositMCommerceRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $phoneNumber,               // телефон для получения кода подтверждения
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $callbackUrl,               // URL для передачи результата создания транзакции в B2B backoffice
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
/** @var DepositResponseData $createdResult */
$createdResult = $billing->createDeposit($requestParams);

... 

// Подтверждение транзакции через одноразовый код из смс на мобильный номер
$requestParams = new DepositMCommerceConfirmRequestData(
    $createdResult->transactionId,
    'confirmationCodeFromSmsOrEmail'
);

// Отправить запрос на подтверждение транзакции
/** @var DepositMCommerceConfirmedResponseData $confirmedResult */
$confirmedResult = $billing->confirmMCommerceDeposit($requestParams);

```

<a id="deposit-h2c-sber-pay"></a>
5. _Создание транзакции депозита через платежный метод SberPay Host2Client_

```php
<?php
use Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositSberPayHost2ClientRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositSberPayHost2ClientRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $customerId,                // ID пользователя, совершающего операцию
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $callbackUrl,               // URL для передачи результата создания транзакции в B2B backoffice
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

<a id="deposit-h2c-sbp"></a>
6. _Создание транзакции депозита через платежный метод SBP Host2Client_

```php
<?php
use Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositSbpHost2ClientRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositSbpHost2ClientRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $customerId,                // ID пользователя, совершающего операцию
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $callbackUrl,               // URL для передачи результата создания транзакции в B2B backoffice
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

<a id="deposit-h2c-havale"></a>
7. _Создание транзакции депозита через платежный метод Havale Host2Client_

```php
<?php
use Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositHavaleHostToClientRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositHavaleHostToClientRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $walletUserId,              // ID пользователя кошелька
    $walletLogin,               // Логин пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $callbackUrl,               // URL для передачи результата создания транзакции
    $redirectSuccessUrl,        // URL для перехода после успешного выполнения действия
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

<a id="deposit-h2c-hay-hay"></a>
8. _Создание транзакции депозита через платежный метод HayHay Host2Client_

```php
<?php
use Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositHayHayHostToClientRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositHayHayHostToClientRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $walletLogin,               // Логин пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $redirectSuccessUrl,        // URL для перехода после успешного выполнения действия
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

<a id="deposit-h2c-emanat"></a>
9. _Создание транзакции депозита через платежный метод eManat Host2Client_

```php
<?php
use Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositHostEManat2ClientRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositHostEManat2ClientRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $walletLogin,               // Логин пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $redirectSuccessUrl,        // URL для перехода после успешного выполнения действия
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

<a id="deposit-h2c-in-card-p2p"></a>
10. _Создание транзакции депозита через платежный метод InCardP2P Host2Client_

```php
<?php
use Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositInCardP2PHostToClientRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositInCardP2PHostToClientRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $walletUserId,              // ID пользователя кошелька
    $walletLogin,               // Логин пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $redirectSuccessUrl,        // URL для перехода после успешного выполнения действия
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

<a id="deposit-h2c-m10"></a>
11. _Создание транзакции депозита через платежный метод M10 Host2Client_

```php
<?php
use Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositM10HostToClientRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositM10HostToClientRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $walletUserId,              // ID пользователя кошелька
    $walletLogin,               // Логин пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $redirectSuccessUrl,        // URL для перехода после успешного выполнения действия
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

<a id="deposit-h2c-papara"></a>
12. _Создание транзакции депозита через платежный метод Papara Host2Client_

```php
<?php
use Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositPaparaHostToClientRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositPaparaHostToClientRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $walletUserId,              // ID пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $redirectSuccessUrl,        // URL для перехода после успешного выполнения действия
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

<a id="deposit-h2c-pey-co"></a>
13. _Создание транзакции депозита через платежный метод PayCo Host2Client_

```php
<?php
use Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositPayCoHostToClientRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositPayCoHostToClientRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $walletUserId,              // ID пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $redirectSuccessUrl,        // URL для перехода после успешного выполнения действия
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

<a id="deposit-h2c-pay-fix"></a>
14. _Создание транзакции депозита через платежный метод Payfix Host2Client_

```php
<?php
use Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositPayfixHostToClientRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositPayfixHostToClientRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $walletUserId,              // ID пользователя кошелька
    $walletLogin,               // Логин пользователя кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $redirectSuccessUrl,        // URL для перехода после успешного выполнения действия
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

<a id="deposit-h2c-pep"></a>
15. _Создание транзакции депозита через платежный метод Pep Host2Client_

```php
<?php
use Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositPepHostToClientRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositPepHostToClientRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $walletUserId,              // ID пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $redirectSuccessUrl,        // URL для перехода после успешного выполнения действия
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

<a id="deposit-h2c-smart-card"></a>
16. _Создание транзакции депозита через платежный метод SmartCard Host2Client_

```php
<?php
use Idynsys\BillingSdk\Data\Requests\Deposits\Host2Client\DepositSmartCardHostToClientRequestData;

// Создать DTO для запроса на создание транзакции для пополнения счета
$requestParams = new DepositSmartCardHostToClientRequestData(
    $amount,                    // сумма пополнения
    $currencyCode,              // валюта суммы пополнения
    $userIpAddress              // IP адрес пользователя
    $userAgent                  // информацию о браузере, операционной системе и устройстве пользователя
    $acceptLanguage             // HTTP-заголовок, используемый для указания предпочтений клиента по языкам
    $fingerprint                // Подпись данных пользователя в запросе. см. https://github.com/fingerprintjs/fingerprintjs
    $walletLogin,               // Логин пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $redirectSuccessUrl,        // URL для перехода после успешного выполнения действия
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
    $trafficType                // Тип трафика для выполнения транзакции в платёжной системе
);

// Создать транзакцию и получить результат
$result = $billing->createDeposit($requestParams);
```

II. _Response_

Если транзакция депозита была создана успешно, то ответом (response)
будет объект класса \Idynsys\BillingSdk\Data\Responses\DepositResponseData:
```
Idynsys\BillingSdk\Data\Responses\DepositResponseData {
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

Есть 2 возможных ответа, которые могут быть отправлены на указанный в запросе callbackUrl:

1. _При выполнении действия без ошибки_

| Поле            | Тип    | Описание                                                                                                                                  |
|-----------------|--------|-------------------------------------------------------------------------------------------------------------------------------------------|
| id              | string | Уникальный идентификатор платежа (UUID)                                                                                                   |
| merchantOrderId | string | Уникальный идентификатор заказа у мерчанта                                                                                                |
| status          | string | Статус платежа. Возможные значения: `NEW`, `ERROR`, `IN_PROGRESS`, `COMPLETED`, `EXPIRED`, `CANCELED`, `CONFIRMED`, `DECLINED`, `PENDING` |
| amount          | number | Сумма платежа                                                                                                                             |
| currency        | string | Валюта платежа                                                                                                                            |

2. _При получении ошибки_

| Поле            | Тип    | Описание                                                                                                                                  |
|-----------------|--------|-------------------------------------------------------------------------------------------------------------------------------------------|
| id              | string | Уникальный идентификатор платежа (UUID) <br> Пример: `f116fde1-cc0e-4b6c-bbe1-6d932c1a5f16`                                               |
| merchantOrderId | string | Уникальный идентификатор заказа у мерчанта <br> Пример: `64321`                                                                           |
| status          | string | Статус платежа. Возможные значения: `NEW`, `ERROR`, `IN_PROGRESS`, `COMPLETED`, `EXPIRED`, `CANCELED`, `CONFIRMED`, `DECLINED`, `PENDING` |
| errorCode       | string | Код ошибки для платежа <br> Пример: `ERROR`                                                                                               |
| errorMessage    | string | Сообщение об ошибке для платежа <br> Пример: `Unathorized`                                                                                |


#### Создать транзакцию для вывода денежных средств со счета

I. Методы, позволяющие создать транзакцию для вывода средства со счета

| №№ | Вид <br/>взаимодействия | Платежный метод | Класс DTO                                                                                                             |
|----|-------------------------|-----------------|-----------------------------------------------------------------------------------------------------------------------|
| 1  | Host2Host               | p2p             | \Idynsys\BillingSdk\Data\Requests\Payouts\PayoutP2PRequestData [см.](#payout-h2h-p2p)                                 |
| 2  | Host2Client             | p2p             | \Idynsys\BillingSdk\Data\Requests\Payouts\Host2Client\PayoutP2PHost2ClientRequestData [см.](#payout-h2c-p2p)          |
| 3  | Host2Host               | Bankcard        | \Idynsys\BillingSdk\Data\Requests\Payouts\PayoutBankcardRequestData [см.](#payout-h2h-bankcad)                        |
| 4  | Host2Client             | SberPay         | \Idynsys\BillingSdk\Data\Requests\Payouts\Host2Client\PayoutSberPayHost2ClientRequestData [см.](#payout-h2c-sber-pay) |
| 5  | Host2Host               | Havale          | \Idynsys\BillingSdk\Data\Requests\Payouts\PayoutHavaleHost2HostRequestData [см.](#payout-h2h-havale)                  |
| 6  | Host2Host               | HayHay          | \Idynsys\BillingSdk\Data\Requests\Deposits\PayoutHayHayHost2HostRequestData [см.](#payout-h2h-hay-hay)                |
| 7  | Host2Host               | eManat          | \Idynsys\BillingSdk\Data\Requests\Payouts\PayoutEManatHost2HostRequestData [см.](#payout-h2h-emanat)                  |
| 8  | Host2Host               | InCardP2P       | \Idynsys\BillingSdk\Data\Requests\Payouts\PayoutInCardP2PHost2HostRequestData [см.](#payout-h2h-in-card-p2p)          |
| 9  | Host2Host               | M10             | \Idynsys\BillingSdk\Data\Requests\Payouts\PayoutM10Host2HostRequestData [см.](#payout-h2h-m10)                        |
| 10 | Host2Host               | Papara          | \Idynsys\BillingSdk\Data\Requests\Payouts\PayoutPaparaHost2HostRequestData [см.](#payout-h2h-papara)                  |
| 11 | Host2Host               | PayCo           | \Idynsys\BillingSdk\Data\Requests\Payouts\PayoutPayCoHost2HostRequestData [см.](#payout-h2h-pay-co)                   |
| 12 | Host2Host               | Payfix          | \Idynsys\BillingSdk\Data\Requests\Payouts\PayoutPayfixHost2HostRequestData [см.](#payout-h2h-payfix)                  |
| 13 | Host2Host               | Pep             | \Idynsys\BillingSdk\Data\Requests\Payouts\PayoutPepHost2HostRequestData [см.](#payout-h2h-pep)                        |
| 14 | Host2Host               | SmartCard       | \Idynsys\BillingSdk\Data\Requests\Payouts\PayoutSmartCardHost2HostRequestData [см.](#payout-h2h-smart-card)           |
| 15 | Host2Host               | SberPay         | \Idynsys\BillingSdk\Data\Requests\Payouts\PayoutSberPayHost2HostRequestData [см.](#payout-h2h-sber-pay)               |
| 16 | Host2Host               | SBP             | \Idynsys\BillingSdk\Data\Requests\Payouts\PayoutSbpHost2HostRequestData [см.](#payout-h2h-sbp)                        |

Некоторые транзакции для вывода средств имеют параметр BankName. Значения для этого параметра можно найти в enum-классе Idynsys\BillingSdk\Enums\BankName
Список возможных значений для наименования банка-получателя:

| Значение       | Наименование банка |
|----------------|-------------------|
| sberbank       | Сбербанк          |
| alfabank       | Альфа-банк        |
| raiffeisen     | Райфайзен-банк    |
| vtb            | ВТБ               |
| gazprombank    | Газпромбанк       |
| tinkoff        | Тинькофф (Т-Банк) |
| rshb           | РСХБ              |
| openbank       | Банк Открытие     |
| sovcombank     | Совкомбанк        |
| rosbank        | Росбанк           |
| postbank       | Почта-банк        |
| ozonbank       | Озон-банк         |
| yandexbank     | Яндекс-банк       |
| tochkabank     | Точка банк        |
| centrinvest    | ЦентрИнвестБанк   |
| mkb            | МКБ               |
| unicreditbank  | ЮникредитБанк     |
| elitebank      | Элитбанк          |
| avangard       | Банк Авангард     |
| psb            | ПСБ               |
| akbars         | Акбарс Банк       |
| homecredit     | ХоумКредитБанк    |
| rnkb           | РНКБ              |
| otpbank        | ОТП Банк          |
| mtsbank        | МТС Банк          |
| rsb            | РСБ               |
| uralsibbank    | Банк Уралсиб      |

<a id="payout-h2h-p2p"></a>
1. _Создание транзакции для вывода средств со счета через метод p2p Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutP2PRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств со счета
$requestParams = new PayoutP2PRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $cardNumber,                // Номер банковской карты, на которую выводятся деньги
    $cardExpiration,            // Месяц и год окончания действия карты (как написано на карте)
    $cardRecipientInfo,         // Данные владельца карты (Имя Фамилия, как написано на карте)
    $bankName,                  // Наименование банка-получателя. Доступные значения находятся в списке выше в этом разделе
    $userId,                    // ID пользователя
    $callbackUrl,               // URL для передачи результата создания транзакции в B2B backoffice
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayout($requestParams);
```

<a id="payout-h2c-p2p"></a>
2. _Создание транзакции для вывода средств со счета через метод p2p Host2Client_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\Host2Client\PayoutP2PHost2ClientRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств со счета
$requestParams = new PayoutP2PHost2ClientRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $recipientAccount,          // Счет получателя
    $callbackUrl,               // URL для передачи результата создания транзакции в B2B backoffice
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Client($requestParams);
```

<a id="payout-h2h-bankcad"></a>
3. _Создание транзакции для вывода средств со счета через метод Bankcard Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutBankcardRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств со счета
$requestParams = new PayoutBankcardRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $cardNumber,                // Номер банковской карты, на которую выводятся деньги
    $cardExpiration,            // Месяц и год окончания действия карты (как написано на карте)
    $cardRecipientInfo,         // Данные владельца карты (Имя Фамилия, как написано на карте)
    $bankName,                  // Наименование банка-получателя. Доступные значения находятся в списке выше в этом разделе
    $userId,                    // ID пользователя
    $callbackUrl                // URL для передачи результата создания транзакции в B2B backoffice
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayout($requestParams);
```

<a id="payout-h2c-sber-pay"></a>
4. _Создание транзакции для вывода средств со счета через метод SberPay Host2Client_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\Host2Client\PayoutSberPayHost2ClientRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств со счета
$requestParams = new PayoutSberPayHost2ClientRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $recipientPhoneNumber,      // Номер телефона получателя
    $callbackUrl,               // URL для передачи результата создания транзакции в B2B backoffice
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Client($requestParams);
```

<a id="payout-h2h-havale"></a>
5. _Создание транзакции для вывода средств со счета через метод Havale Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutHavaleHost2HostRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств
$requestParams = new PayoutHavaleHost2HostRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $walletUserId,              // ID пользователя кошелька
    $walletLogin,               // Логин пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька    
    $callbackUrl,               // URL для передачи результата создания транзакции
    $BankIbanNo,                // IBAN банка
    $cardNumber,                // Номер карты
    $cardExpiration,            // Дата окончания действия карты
    $userBirthday,              // День рождения пользователя
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Host($requestParams);
```

<a id="payout-h2h-hay-hay"></a>
6. _Создание транзакции для вывода средств со счета через метод HayHay Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutHayHayHost2HostRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств
$requestParams = new PayoutHayHayHost2HostRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $bankId,                    // ID банка
    $cardId,                    // ID карты
    $walletAccountNumber,       // Номер счета кошелька
    $walletUserId,              // ID пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька    
    $callbackUrl,               // URL для передачи результата создания транзакции
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Host($requestParams);
```

<a id="payout-h2h-emanat"></a>
7. _Создание транзакции для вывода средств со счета через метод eManat Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutEManatHost2HostRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств
$requestParams = new PayoutEManatHost2HostRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $phoneNumber,               // Телефонный номер пользователя
    $walletLogin,               // Логин пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька    
    $callbackUrl,               // URL для передачи результата создания транзакции
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Host($requestParams);
```

<a id="payout-h2h-in-card-p2p"></a>
8. _Создание транзакции для вывода средств со счета через метод InCardP2P Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutInCardP2PHost2HostRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств
$requestParams = new PayoutInCardP2PHost2HostRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $bankId,                    // ID банка
    $cardNumber,                // Номер банковской карты, на которую выводятся деньги
    $cardExpiration,            // Месяц и год окончания действия карты (как написано на карте)
    $walletUserId,              // ID пользователя кошелька
    $walletLogin,               // Логин пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька    
    $callbackUrl,               // URL для передачи результата создания транзакции
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Host($requestParams);
```

<a id="payout-h2h-m10"></a>
9. _Создание транзакции для вывода средств со счета через метод M10 Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutM10Host2HostRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств
$requestParams = new PayoutM10Host2HostRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $walletUserId,              // ID пользователя кошелька
    $walletLogin,               // Логин пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька    
    $walletAccountNumber        // Номер счета кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Host($requestParams);
```

<a id="payout-h2h-papara"></a>
10. _Создание транзакции для вывода средств со счета через метод Papara Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutPaparaHost2HostRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств
$requestParams = new PayoutPaparaHost2HostRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $walletUserId,              // ID пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька    
    $walletAccountNumber        // Номер счета кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Host($requestParams);
```

<a id="payout-h2h-pay-co"></a>
11. _Создание транзакции для вывода средств со счета через метод PayCo Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutPayCoHost2HostRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств
$requestParams = new PayoutPayCoHost2HostRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $walletUserId,              // ID пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька    
    $walletAccountNumber        // Номер счета кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Host($requestParams);
```

<a id="payout-h2h-payfix"></a>
12. _Создание транзакции для вывода средств со счета через метод Payfix Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutPayfixHost2HostRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств
$requestParams = new PayoutPayfixHost2HostRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $walletUserId,              // ID пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька    
    $walletAccountNumber        // Номер счета кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Host($requestParams);
```

<a id="payout-h2h-pep"></a>
13. _Создание транзакции для вывода средств со счета через метод Pep Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutPepHost2HostRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств
$requestParams = new PayoutPepHost2HostRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $bankId,                    // ID банка
    $cardId,                    // ID карты    
    $walletAccountNumber        // Номер счета кошелька
    $walletUserId,              // ID пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька
    $callbackUrl,               // URL для передачи результата создания транзакции
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Host($requestParams);
```

<a id="payout-h2h-smart-card"></a>
14. _Создание транзакции для вывода средств со счета через метод SmartCard Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutSmartCardHost2HostRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств
$requestParams = new PayoutSmartCardHost2HostRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $cardNumber,                // Номер банковской карты, на которую выводятся деньги
    $cardExpiration,            // Месяц и год окончания действия карты (как написано на карте)
    $walletLogin,               // Логин пользователя кошелька
    $walletUserFullName,        // ФИО пользователя кошелька    
    $callbackUrl,               // URL для передачи результата создания транзакции
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Host($requestParams);
```

<a id="payout-h2h-sber-pay"></a>
15. _Создание транзакции для вывода средств со счета через метод SberPay Host2Host_
```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutSberPayHost2HostRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств
$requestParams = new PayoutSberPayHost2HostRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $cardNumber,                // Номер банковской карты, на которую выводятся деньги
    $cardRecipientInfo,         // Данные владельца карты (Имя Фамилия, как написано на карте)
    $userId,                    // ID пользователя
    $ipAddress,                 // IP адрес пользователя
    $userAgent,                 // сведения об устройстве, операционной системе, типе браузера и его версии и т.д.
    $callbackUrl,               // URL для передачи результата создания транзакции
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Host($requestParams);
```
<a id="payout-h2h-sbp"></a>
16. _Создание транзакции для вывода средств со счета через метод SBP Host2Host_


```php
<?php

use Idynsys\BillingSdk\Data\Requests\Payouts\PayoutSbpHost2HostRequestData;
use Idynsys\BillingSdk\Data\Responses\PayoutResponseData;

// Создать DTO для запроса на создание транзакции для вывода средств
$requestParams = new PayoutSbpHost2HostRequestData(
    $amount,                    // сумма вывода
    $currencyCode,              // валюта суммы вывода
    $bankName,                  // Наименование банка-получателя. Доступные значения находятся в списке выше в этом разделе
    $userId,                    // ID пользователя
    $callbackUrl,               // URL для передачи результата создания транзакции
    $merchantOrderId,           // идентификатор внутреннего документа, на основе которого создается транзакция
    $merchantOrderDescription,  // описание документа, на основе которого создается транзакция
);

// Создать транзакцию и получить результат
/** @var PayoutResponseData $result */
$result = $billing->createPayoutHost2Host($requestParams);
```

II. _Response_

Если операция выполнена успешно, то ответ придет в виде объекта класса PayoutResponseData:
```
Idynsys\BillingSdk\Data\Responses\PayoutResponseData {
  +status: "SUCCESS"
  +transactionId: "338263f6-e1af-4a25-aa38-ac0ea724be02"
  +error: null
}
```

#### Получить данные транзакции
Для любой созданной транзакции можно проверить статус, тип, валюту, сумму, выполнив следующие действия:
```php
<?php
use Idynsys\BillingSdk\Data\Requests\Transactions\TransactionRequestData;
use Idynsys\BillingSdk\Data\Responses\TransactionData;

// Создать DTO для запроса данных транзакции
$requestParams = new TransactionRequestData('50943073-3426-4e00-b147-1d21852c0e22');

// Выполнить запрос для получения данных транзакции
/** @var TransactionData $result */
$result = $billing->getTransactionData($requestParams);

```

#### Обработка исключительных ситуаций
При запросе к системе могут возникнуть ошибки, связанные с некорректно отправленными данными
или невозможностью выполнить операцию. Все ошибки возвращаются через объект-исключение
\Idynsys\BillingSdk\Exceptions\ExceptionHandler. Обработать ошибки можно следующим образом:
```php
<?php
use Idynsys\BillingSdk\Exceptions\BilllingSdkException;
use Throwable;

try {
    // Выполнить запрос из описанных в п. 2.
} catch (BilllingSdkException $exception) {
    // обработать ошибку
} catch (Throwable $exception) {
    // обработать ошибку
}
```
