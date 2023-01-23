<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @property string name */
class Carrier extends Model
{
    public const USP = 'USP';
    public const DHL = 'DHL';

    public $timestamps = false;

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }
}
