<?php

namespace App\Console\Commands;

use App\Models\Delivery;
use Illuminate\Console\Command;

class CreateRandomDeliveriesCommand extends Command
{
    private const DELIVERY_COUNT = 10;

    protected $signature = 'delivery-crm:delivery:create-rand';

    public function handle(): int
    {
        //Make sure that carrier `php artisan db:seed --class=CarrierSeeder` run before
        $deliveries = Delivery::factory(self::DELIVERY_COUNT)->create();

        dump($deliveries->toArray());

        return Command::SUCCESS;
    }
}
