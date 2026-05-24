<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                 // 'Batch 1', 'Angkatan 2025-01'
            $table->unsignedSmallInteger('year');                   // 2025, 2026
            $table->string('institution_code', 20)->default('POLRI');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('max_parameters')->default(4); // max sesi tes per tahun (2-4)
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->timestamps();

            $table->index(['year', 'institution_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
