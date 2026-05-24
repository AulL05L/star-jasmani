<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('injury_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_id')
                  ->constrained('athletes')
                  ->cascadeOnDelete();
            $table->foreignId('recorded_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->string('bagian_tubuh');
            $table->text('deskripsi_cedera');
            $table->date('tanggal_cedera');
            $table->date('tanggal_sembuh')->nullable();
            $table->enum('status', ['aktif', 'pulih', 'monitoring'])->default('aktif');
            $table->text('catatan_medis')->nullable();
            $table->timestamps();

            $table->index(['athlete_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('injury_tracks');
    }
};