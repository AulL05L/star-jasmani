<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kebugaran_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('kebugaran_sessions')->cascadeOnDelete();
            $table->enum('parameter', [
                'bmi',
                'komposisi_otot',
                'komposisi_lemak',
                'push_up',
                'sit_up',
                'squat',
                'sit_and_reach',
            ]);
            $table->decimal('value', 7, 2);
            $table->timestamps();

            $table->unique(['session_id', 'parameter']);
            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kebugaran_scores');
    }
};
