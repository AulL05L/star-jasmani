<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bmi_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_id')->constrained('athletes')->cascadeOnDelete();
            $table->foreignId('recorded_by')->constrained('users')->restrictOnDelete();
            $table->decimal('height_cm', 5, 1);
            $table->decimal('weight_kg', 5, 1);
            $table->decimal('bmi_value', 5, 2);
            $table->enum('bmi_status', ['Kurang', 'Normal', 'Gemuk', 'Obesitas']);
            $table->date('recorded_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['athlete_id', 'recorded_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bmi_records');
    }
};