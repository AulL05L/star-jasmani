<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('athletes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->enum('gender', ['pria', 'wanita']);
            $table->string('nik', 20)->nullable()->unique();
            $table->date('birth_date')->nullable();
            $table->string('phone', 20)->nullable();
            $table->decimal('height_cm', 5, 1)->nullable();
            $table->decimal('weight_kg', 5, 1)->nullable();
            $table->string('target_institution')->nullable();
            $table->string('batch')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('gender');
            $table->index('batch');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('athletes');
    }
};