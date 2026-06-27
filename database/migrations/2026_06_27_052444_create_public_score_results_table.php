<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_score_results', function (Blueprint $table) {
            $table->id();
            $table->uuid('token')->unique();
            $table->string('calculator_type', 20)->default('polri');
            $table->string('gender', 10);

            // Raw inputs
            $table->unsignedSmallInteger('raw_lari_meter');
            $table->unsignedTinyInteger('raw_pullup_reps');
            $table->unsignedTinyInteger('raw_situp_reps');
            $table->unsignedTinyInteger('raw_pushup_reps');
            $table->decimal('raw_shuttle_seconds', 5, 2);
            $table->decimal('raw_renang_seconds', 6, 2);

            // Component scores
            $table->decimal('score_lari',      5, 2)->nullable();
            $table->decimal('score_pullup',    5, 2)->nullable();
            $table->decimal('score_situp',     5, 2)->nullable();
            $table->decimal('score_pushup',    5, 2)->nullable();
            $table->decimal('score_shuttle',   5, 2)->nullable();
            $table->decimal('score_jasmani_b', 5, 2)->nullable();
            $table->decimal('score_renang',    5, 2)->nullable();
            $table->decimal('score_ukg_avg',   5, 2)->nullable();
            $table->decimal('score_final',     5, 2)->nullable();

            // Grade
            $table->char('grade', 1)->nullable();
            $table->string('grade_label', 30)->nullable();
            $table->boolean('is_lulus')->default(false);

            // Snapshot weights (always 80/20 for POLRI public calculator)
            $table->decimal('ukg_weight',   5, 2)->default(80.00);
            $table->decimal('renang_weight', 5, 2)->default(20.00);

            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_score_results');
    }
};
