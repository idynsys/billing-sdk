<?php

namespace Idynsys\BillingSdk;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Idynsys\BillingSdk\Data\Requests\RequestData;
use Idynsys\BillingSdk\Exceptions\BillingSdkException;
use Idynsys\BillingSdk\Exceptions\ExceptionHandler;
use JsonException;
use Throwable;

/**
 * Класс для выполнения запросов к B2B backoffice
 */
class Client extends GuzzleClient
{
    // Содержимое ответа выполненного запроса
    private string $content;

    // Exception возникший при выполнении запроса
    private ?Exception $error = null;

    /**
     * @param RequestData $data
     * @param bool $throwException
     * @return $this
     * @throws BillingSdkException
     */
    public function sendRequestToSystem(RequestData $data, bool $throwException = true): self
    {
        $this->error = null;

        try {
            $res = $this->request($data->getMethod(), $data->getUrl(), $data->getData());

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
        if (!isset($this->content) || $this->hasError() || empty($this->content)) {
            return null;
        }

        $data = json_decode($this->content, true, 512, JSON_THROW_ON_ERROR);

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
        return !is_null($this->error);
    }

    /**
     * Получить ошибку запроса, если она произошла
     *
     * @return array|null
     */
    public function getError(): ?array
    {
        if (!$this->hasError()) {
            return null;
        }

        return $this->error->getError();
    }
}
