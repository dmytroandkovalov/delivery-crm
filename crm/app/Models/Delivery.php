<?php

namespace App\Models;

use App\Models\Interfaces\HasWeight;
use App\Observers\DeliveryObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Carrier $carrier
 * @property int $weight_gram
 * @property float $price_usd
 * @property float $price_eur
 * @property string $description
 */
class Delivery extends Model implements HasWeight
{
    use HasFactory;

    public $fillable = [
        'carrier_id',
        'weight_gram',
        'description',
        'price_usd',
        'price_eur',
    ];

    protected $dispatchesEvents = [
        'saving' => DeliveryObserver::class,
    ];

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class);
    }

    public function getWeightGram(): int
    {
        return $this->weight_gram;
    }

    public function isFilledPrice():bool
    {
        return $this->price_usd || $this->price_eur;
    }
}
