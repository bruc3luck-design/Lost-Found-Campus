<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('tipe', ['hilang', 'temuan']);
            $table->string('judul');
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->foreignId('location_id')->constrained()->restrictOnDelete();
            $table->foreignId('pickup_point_id')
                  ->nullable()
                  ->constrained('locations')
                  ->nullOnDelete();
            $table->date('tanggal');
            $table->enum('status', ['hilang', 'ditemukan', 'diklaim', 'dikembalikan'])
                  ->default('hilang');
            $table->boolean('disembunyikan')->default(false);
            $table->timestamps();

            $table->index(['tipe', 'status', 'disembunyikan']);
            $table->index('category_id');
            $table->index('location_id');
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
