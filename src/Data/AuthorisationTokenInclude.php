<?php

namespace Idynsys\BillingSdk\Data;

/**
 * Интерфейс для запросов с аутентификацищнным токеном
 */
interface AuthorisationTokenInclude
{
    /**
     * Установить аутентификационный токен
     *
     * @param $token
     * @return void
     */
    public function setToken($token): void;
}