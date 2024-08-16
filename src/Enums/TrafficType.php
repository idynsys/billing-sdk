<?php

namespace Idynsys\BillingSdk\Enums;

final class TrafficType
{
    public const FDT = 'fdt'; // первичный трафик (для непроверенных пользователей делающих оплату первый раз)

    public const TRUSTED = 'trusted'; // для доверенных пользователей
}
