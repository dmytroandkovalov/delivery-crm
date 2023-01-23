<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('carriers', static function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->nullable(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carriers');
    }
};
