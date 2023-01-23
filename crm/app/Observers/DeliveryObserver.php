<?php

namespace App\Observers;

use App\Models\Delivery;
use App\Services\Factories\DeliveryCalculatorFactory;

readonly class DeliveryObserver
{
    public function saving(Delivery $delivery): void
    {
        if ($delivery->isFilledPrice()) {
            return;
        }

        /** @var  DeliveryCalculatorFactory $deliveryCalculatorFactory */
        $deliveryCalculatorFactory = app()->make(DeliveryCalculatorFactory::class);
        $calculator = $deliveryCalculatorFactory->make($delivery->carrier);

        $delivery->price_usd = $calculator->getPriceUsd($delivery);
        $delivery->price_eur = $calculator->getPriceEur($delivery);
    }
}
