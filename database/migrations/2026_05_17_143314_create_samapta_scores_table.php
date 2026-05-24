<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samapta_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_id')->constrained('athletes')->cascadeOnDelete();
            $table->foreignId('assessed_by')->constrained('users')->restrictOnDelete();
            $table->string('institution')->default('POLRI');
            $table->string('session_label')->nullable();
            $table->date('assessment_date');

            $table->integer('raw_lari_meter')->nullable();
            $table->integer('raw_pushup_reps')->nullable();
            $table->integer('raw_situp_reps')->nullable();
            $table->integer('raw_pullup_reps')->nullable();
            $table->decimal('raw_shuttle_seconds', 6, 2)->nullable();
            $table->decimal('raw_renang_seconds', 7, 2)->nullable();

            $table->decimal('score_lari', 5, 2)->nullable();
            $table->decimal('score_pushup', 5, 2)->nullable();
            $table->decimal('score_situp', 5, 2)->nullable();
            $table->decimal('score_pullup', 5, 2)->nullable();
            $table->decimal('score_shuttle', 5, 2)->nullable();
            $table->decimal('score_renang', 5, 2)->nullable();
            $table->decimal('score_ukg_avg', 5, 2)->nullable();
            $table->decimal('score_final', 5, 2)->nullable();
            $table->string('grade', 2)->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('athlete_id');
            $table->index('assessment_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samapta_scores');
    }
};