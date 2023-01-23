<?php

namespace Database\Seeders;

use App\Models\Carrier;
use Illuminate\Database\Seeder;

class CarrierSeeder extends Seeder
{
    private const CARRIER_NAMES = [
        Carrier::USP,
        Carrier::DHL,
    ];

    public function run(): void
    {
        foreach (self::CARRIER_NAMES as $carrierName) {
            Carrier::updateOrCreate([
                'name' => $carrierName
            ]);
        }
    }
}
