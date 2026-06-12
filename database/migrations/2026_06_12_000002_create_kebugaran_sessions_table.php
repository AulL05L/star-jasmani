<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kebugaran_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('kebugaran_periods')->cascadeOnDelete();
            $table->unsignedSmallInteger('session_number');   // 1, 2, 3, ...
            $table->date('date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('period_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kebugaran_sessions');
    }
};
