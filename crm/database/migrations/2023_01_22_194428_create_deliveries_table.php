<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Carrier;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Carrier::class)->nullable(false);
            $table->unsignedBigInteger('weight_gram')->nullable(false)->index();
            $table->decimal('price_usd', 10)->index();
            $table->decimal('price_eur', 10)->index();
            $table->tinyText('description')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
