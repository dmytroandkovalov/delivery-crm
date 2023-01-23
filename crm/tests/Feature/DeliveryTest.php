<?php

namespace Tests\Feature;

use App\Models\Carrier;
use App\Models\Delivery;
use App\Models\User;
use App\Services\Factories\DeliveryCalculatorFactory;
use LaravelJsonApi\Testing\MakesJsonApiRequests;

class DeliveryTest extends BaseFeatureTest
{
    use MakesJsonApiRequests;

    protected string $baseUrl = 'api/v1/deliveries';

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'CarrierSeeder']);

        $this->actingAs(User::factory()->create());
    }

    public function test_read_all_deliveries_without_filter(): void
    {
        $models = Delivery::factory()->count(10)->create();

        $response = $this->jsonApi()
            ->expects('deliveries')
            ->get($this->baseUrl);

        $response->assertStatus(200);
        $response->assertFetchedMany($models);
    }

    public function test_read_deliveries_with_carrier_filter(): void
    {
        Delivery::factory()->count(30)->create();
        $carrier = Carrier::inRandomOrder()->first();

        $response = $this->jsonApi()
            ->expects('deliveries')
            ->filter([
                'carrier' => $carrier->id
            ])
            ->includePaths('carrier')
            ->get($this->baseUrl);

        $response->assertStatus(200);

        $responseDeliveries = $response->json('data');

        foreach ($responseDeliveries as $responseDelivery) {
            $expectedCarrierId = $carrier->id;
            $actualCarrierId = $responseDelivery['relationships']['carrier']['data']['id'];
            $this->assertEquals($expectedCarrierId, $actualCarrierId);
        }
    }

    public function test_read_deliveries_with_price_usd_gt_filter(): void
    {
        Delivery::factory()->count(30)->create();

        $priceUsdGtThan = random_int(0, 100) / 10;

        $response = $this->jsonApi()
            ->expects('deliveries')
            ->filter([
                'price_usd_gt' => $priceUsdGtThan,
            ])
            ->get($this->baseUrl);

        $response->assertStatus(200);

        $responseDeliveries = $response->json('data');


        foreach ($responseDeliveries as $responseDelivery) {
            $actualPriceUsd = $responseDelivery['attributes']['price_usd'];

            $this->assertTrue($actualPriceUsd > $priceUsdGtThan);
        }
    }

    public function test_read_deliveries_with_price_usd_lt_filter(): void
    {
        Delivery::factory()->count(30)->create();

        $priceUsdLtThan = random_int(0, 25000) / 10;

        $response = $this->jsonApi()
            ->expects('deliveries')
            ->filter([
                'price_usd_lt' => $priceUsdLtThan,
            ])
            ->get($this->baseUrl);

        $response->assertStatus(200);

        $responseDeliveries = $response->json('data');


        foreach ($responseDeliveries as $responseDelivery) {
            $actualPriceUsd = $responseDelivery['attributes']['price_usd'];

            $this->assertTrue($actualPriceUsd < $priceUsdLtThan);
        }
    }

    public function test_read_deliveries_with_weight_gram_gt_filter(): void
    {
        Delivery::factory()->count(30)->create();

        $weightGramGtThan = random_int(150, 100000);

        $response = $this->jsonApi()
            ->expects('deliveries')
            ->filter([
                'weight_gram_gt' => $weightGramGtThan,
            ])
            ->get($this->baseUrl);

        $response->assertStatus(200);

        $responseDeliveries = $response->json('data');


        foreach ($responseDeliveries as $responseDelivery) {
            $actualWeightGram = $responseDelivery['attributes']['weight_gram'];

            $this->assertTrue($actualWeightGram > $weightGramGtThan);
        }
    }

    public function test_read_deliveries_with_weight_gram_lt_filter(): void
    {
        Delivery::factory()->count(30)->create();

        $weightGramLtThan = random_int(0, 1500);

        $response = $this->jsonApi()
            ->expects('deliveries')
            ->filter([
                'weight_gram_lt' => $weightGramLtThan,
            ])
            ->get($this->baseUrl);

        $response->assertStatus(200);

        $responseDeliveries = $response->json('data');

        foreach ($responseDeliveries as $responseDelivery) {
            $actualWeightGramm = $responseDelivery['attributes']['weight_gram'];

            $this->assertTrue($actualWeightGramm < $weightGramLtThan);
        }
    }

    public function test_create_new_delivery(): void
    {
        $carrier = Carrier::inRandomOrder()->first();
        $weightGram = random_int(10, 10000);
        $description = 'test delivery';
        $data = [
            'type' => 'deliveries',
            'attributes' => [
                'weight_gram' => $weightGram,
                'description' => $description,
            ],
            'relationships' => [
                'carrier' => [
                    'data' => [
                        'type' => 'carriers',
                        'id' => (string)$carrier->id
                    ]
                ]
            ]
        ];

        $response = $this
            ->jsonApi()
            ->withData($data)
            ->post($this->baseUrl);

        $response->assertStatus(201);

        $createdDeliveryId = $response->json('data.id');

        $deliveryCalculatorFactory = app()->make(DeliveryCalculatorFactory::class);
        $calculator = $deliveryCalculatorFactory->make($carrier);

        /** @var Delivery $expectedDelivery */
        $expectedDelivery = Delivery::factory()->makeOne([
            'weight_gram' => $weightGram,
            'description' => $description,
        ]);

        $this->assertDatabaseHas('deliveries', [
            'id' => $createdDeliveryId,
            'weight_gram' => $weightGram,
            'price_usd' => round($calculator->getPriceUsd($expectedDelivery), 2),
            'price_eur' => round($calculator->getPriceEur($expectedDelivery), 2),
            'description' => $description,
        ]);
    }

    public function test_update_delivery(): void
    {
        $carrier = Carrier::inRandomOrder()->first();
        $weightGram = random_int(10, 10000);
        $description = 'test delivery';

        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->createOne([
            'carrier_id' => $carrier->id,
            'weight_gram' => $weightGram,
            'description' => $description,
        ]);

        $newCarrier = Carrier::where('id', '!=', $carrier->id)->first();
        $newWeightGram = $weightGram + random_int(10, 200);
        $newDescription = 'test delivery 2';
        $newPriceUsd = $delivery->price_usd + random_int(0, 50);
        $newPriceEur = $delivery->price_eur + random_int(0, 50);

        $data = [
            'type' => 'deliveries',
            'id' => (string)$delivery->id,
            'attributes' => [
                'weight_gram' => $newWeightGram,
                'description' => $newDescription,
                'price_usd' => $newPriceUsd,
                'price_eur' => $newPriceEur,
            ],
            'relationships' => [
                'carrier' => [
                    'data' => [
                        'type' => 'carriers',
                        'id' => (string)$newCarrier->id
                    ]
                ]
            ]
        ];

        $response = $this
            ->jsonApi()
            ->withData($data)
            ->withData($data)
            ->patch(sprintf('%s/%s', $this->baseUrl, $delivery->getRouteKey()));

        $response->assertStatus(200);

        $this->assertDatabaseHas('deliveries', [
            'id' => $delivery->id,
            'weight_gram' => $newWeightGram,
            'price_usd' => round($newPriceUsd, 2),
            'price_eur' => round($newPriceEur, 2),
            'description' => $newDescription,
        ]);
    }
}
