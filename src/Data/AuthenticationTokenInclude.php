<?php

namespace Idynsys\BillingSdk\Data;

/**
 * Интерфейс для запросов с токеном аутентификации
 */
interface AuthenticationTokenInclude
{
    /**
     * Установить аутентификационный токен
     *
     * @param $token
     * @return void
     */
    public function setToken($token): void;
}