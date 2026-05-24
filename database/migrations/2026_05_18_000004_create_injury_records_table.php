<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('injury_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_id')->constrained('athletes')->cascadeOnDelete();
            $table->foreignId('recorded_by')->constrained('users')->restrictOnDelete();
            $table->string('injury_type');              // 'Cedera Hamstring', 'Keseleo Kaki', dll.
            $table->text('description')->nullable();    // catatan detail cedera
            $table->date('injury_date')->nullable();
            $table->text('recovery_notes')->nullable(); // catatan pemulihan
            $table->boolean('is_recovered')->default(false);
            $table->date('recovered_at')->nullable();
            $table->timestamps();

            $table->index('athlete_id');
            $table->index(['athlete_id', 'is_recovered']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('injury_records');
    }
};
