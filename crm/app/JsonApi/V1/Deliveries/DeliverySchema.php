<?php

namespace App\JsonApi\V1\Deliveries;

use App\Models\Delivery;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class DeliverySchema extends Schema
{
    public static string $model = Delivery::class;

    public function fields(): array
    {
        return [
            ID::make(),
            BelongsTo::make('carrier')->type('carriers'),
            Number::make('weight_gram'),
            Number::make('price_usd'),
            Number::make('price_eur'),
            Str::make('description'),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),
        ];
    }

    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
            Where::make('carrier', 'carrier_id'),
            Where::make('weight_gram_gt', 'weight_gram')->gt(),
            Where::make('weight_gram_lt', 'weight_gram')->lt(),
            Where::make('price_usd_gt', 'price_usd')->gt(),
            Where::make('price_usd_lt', 'price_usd')->lt(),
            Where::make('price_eur_gt', 'price_eur')->gt(),
            Where::make('price_eur_lt', 'price_eur')->lt(),
            Where::make('description'),
        ];
    }

    public function pagination(): ?Paginator
    {
        return PagePagination::make();
    }
}
