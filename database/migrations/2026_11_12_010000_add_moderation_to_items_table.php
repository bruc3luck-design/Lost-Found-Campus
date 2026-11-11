<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->enum('status_moderasi', ['menunggu', 'disetujui', 'ditolak'])
                  ->default('menunggu')
                  ->after('status');
            $table->text('catatan_moderasi')
                  ->nullable()
                  ->after('status_moderasi');
            $table->timestamp('moderated_at')
                  ->nullable()
                  ->after('catatan_moderasi');
            $table->foreignId('moderated_by')
                  ->nullable()
                  ->after('moderated_at')
                  ->constrained('users')
                  ->nullOnDelete();

            $table->index('status_moderasi');
            $table->index(['status_moderasi', 'disembunyikan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['moderated_by']);
            $table->dropIndex(['status_moderasi']);
            $table->dropIndex(['status_moderasi', 'disembunyikan']);
            $table->dropColumn([
                'status_moderasi',
                'catatan_moderasi',
                'moderated_at',
                'moderated_by',
            ]);
        });
    }
};
