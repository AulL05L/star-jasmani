<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Ganti enum → varchar supaya parameter bisa ditambah tanpa alter enum
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kebugaran_scores', function (Blueprint $table) {
            $table->string('parameter', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('kebugaran_scores', function (Blueprint $table) {
            $table->enum('parameter', [
                'bmi','komposisi_otot','komposisi_lemak',
                'push_up','sit_up','squat','sit_and_reach',
            ])->change();
        });
    }
};
