<?php

namespace App\Services\DeliveryCalculators;

use App\Models\Interfaces\HasWeight;
use App\Services\Converters\CurrencyConverter;
use App\Services\DeliveryCalculators\Interfaces\DHLDeliveryCalculatorInterface;
class DHLDeliveryCalculator implements DHLDeliveryCalculatorInterface
{
    private const  PRICE_PER_100_GRAM_EUR = 0.33;

    public function getPriceUsd(HasWeight $model): float
    {
        return CurrencyConverter::fromEurToUsd(
            $this->getPriceEur($model)
        );
    }

    public function getPriceEur(HasWeight $model): float
    {
        $weightGram = $model->getWeightGram();
        $wholeHundredGramCount = (int)($weightGram / 100);
        $leftHundredGramCount = ceil(($weightGram % 100) / 100);

        return ($wholeHundredGramCount + $leftHundredGramCount) * self::PRICE_PER_100_GRAM_EUR;
    }
}
