<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            // batch_id FK (nullable agar data lama tidak rusak selama migrasi)
            $table->foreignId('batch_id')
                  ->nullable()
                  ->after('target_institution')
                  ->constrained('batches')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Batch::class);
            $table->dropColumn('batch_id');
        });
    }
};
