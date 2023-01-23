<?php

namespace App\Services\DeliveryCalculators\Interfaces;

use App\Models\Interfaces\HasWeight;

interface DeliveryCalculatorInterface
{
    public function getPriceUsd(HasWeight $model): float;

    public function getPriceEur(HasWeight $model): float;
}
