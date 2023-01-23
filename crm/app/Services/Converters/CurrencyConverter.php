<?php

namespace App\Services\Converters;

class CurrencyConverter
{
    public static function fromEurToUsd(float $eur): float
    {
        return $eur * config('app.usd_to_eur_course');
    }

    public static function fromUsdToEur(float $usd): float
    {
        return $usd / config('app.usd_to_eur_course');
    }
}
