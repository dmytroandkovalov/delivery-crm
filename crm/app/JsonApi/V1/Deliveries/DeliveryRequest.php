<?php

namespace App\JsonApi\V1\Deliveries;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class DeliveryRequest extends ResourceRequest
{
    public function rules(): array
    {
        return [
            'carrier' => ['required', JsonApiRule::toOne()],
            'weight_gram' => ['required', 'numeric', 'gt:0'],
            'price_usd' => ['numeric', 'gt:0'],
            'price_eur' => ['numeric', 'gt:0'],
            'description' => ['string'],
        ];
    }
}
