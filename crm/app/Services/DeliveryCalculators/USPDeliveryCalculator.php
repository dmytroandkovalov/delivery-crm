<?php

namespace App\Services\DeliveryCalculators;

use App\Models\Interfaces\HasWeight;
use App\Services\Converters\CurrencyConverter;
use App\Services\DeliveryCalculators\Interfaces\USPDeliveryCalculatorInterface;

class USPDeliveryCalculator implements USPDeliveryCalculatorInterface
{
    private const PRICE_BEFORE_4_5_KG_USD = 2.00;
    private const PRICE_AFTER_4_5_KG_USD = 3.00;

    public function getPriceUsd(HasWeight $model): float
    {
        $weightKg = $model->getWeightGram() / 1000;
        if ($weightKg <= 4.5) {
            return $weightKg * self::PRICE_BEFORE_4_5_KG_USD;
        }

        return $weightKg * self::PRICE_AFTER_4_5_KG_USD;
    }

    public function getPriceEur(HasWeight $model): float
    {
        return CurrencyConverter::fromUsdToEur(
            $this->getPriceUsd($model)
        );
    }
}
