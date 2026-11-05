<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('keterangan')->nullable();
            $table->boolean('is_pickup_point')->default(false);
            $table->timestamps();

            $table->index('is_pickup_point');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
