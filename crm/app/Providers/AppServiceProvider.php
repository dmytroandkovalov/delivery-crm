<?php

namespace App\Providers;

use App\Services\DeliveryCalculators\DHLDeliveryCalculator;
use App\Services\DeliveryCalculators\Interfaces\DHLDeliveryCalculatorInterface;
use App\Services\DeliveryCalculators\Interfaces\USPDeliveryCalculatorInterface;
use App\Services\DeliveryCalculators\USPDeliveryCalculator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DHLDeliveryCalculatorInterface::class, DHLDeliveryCalculator::class);
        $this->app->bind(USPDeliveryCalculatorInterface::class, USPDeliveryCalculator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
