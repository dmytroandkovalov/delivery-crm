<?php

namespace App\Services\Factories;

use App\Models\Carrier;
use App\Services\DeliveryCalculators\Interfaces\DeliveryCalculatorInterface;
use App\Services\DeliveryCalculators\Interfaces\DHLDeliveryCalculatorInterface;
use App\Services\DeliveryCalculators\Interfaces\USPDeliveryCalculatorInterface;
use Illuminate\Contracts\Container\BindingResolutionException;

class DeliveryCalculatorFactory
{
    /**
     * @throws BindingResolutionException
     */
    public function make(Carrier $carrier): DeliveryCalculatorInterface
    {
        switch ($carrier->name) {
            case Carrier::USP:
                return app()->make(USPDeliveryCalculatorInterface::class);

            case Carrier::DHL:
                return app()->make(DHLDeliveryCalculatorInterface::class);
        }

        throw new \LogicException(sprintf('There is no calculator service for carrier "%s"', $carrier->name));
    }
}
