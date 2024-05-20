<?php

namespace Idynsys\BillingSdk;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Idynsys\BillingSdk\Data\Requests\RequestDataContract;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;
use Idynsys\BillingSdk\Exceptions\ExceptionHandler;
use JsonException;
use Throwable;

/**
 * Класс для выполнения запросов к B2B backoffice
 */
class Client
{
    // Содержимое ответа выполненного запроса
    private string $content = '';

    // Exception возникший при выполнении запроса
    private ?Exception $error = null;

    private GuzzleClient $requestClient;

    public function __construct(array $config = [])
    {
        $this->requestClient = new GuzzleClient($config);
    }

    /**
     * @param RequestDataContract $data
     * @param bool $throwException
     * @return $this
     * @throws BillingSdkException
     */
    public function sendRequestToSystem(RequestDataContract $data, bool $throwException = true): self
    {
        $this->error = null;

        try {
            $res = $this->requestClient->request($data->getMethod(), $data->getUrl(), $data->getData());

            $this->content = $res->getBody()->getContents();
        } catch (Throwable $exception) {
            $handler = new ExceptionHandler($exception);
            $this->error = $handler->handle();
        }

        if ($this->error && $throwException) {
            throw $this->error;
        }

        return $this;
    }

    /**
     * Получить результат запроса. Если произошла ошибка, то вернется null
     *
     * @param string|null $key
     * @return string[]|null
     * @throws JsonException
     */
    public function getResult(?string $key = null): ?array
    {
        if (empty($this->content) && !$this->hasError()) {
            return [];
        }

        if (!isset($this->content) || empty($this->content) || $this->hasError()) {
            return null;
        }

        try {
            $data = json_decode($this->content, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            throw new BillingSdkException(
                'Получены данные в некорректном формате. Ожидается json-формат.',
                415,
                $exception
            );
        }


        if ($key && is_array($data)) {
            $data = [$key => array_key_exists($key, $data) ? $data[$key] : ''];
        }

        return $data;
    }

    /**
     * Проверить наличие ошибки в запросе
     *
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->error !== null;
    }
}
