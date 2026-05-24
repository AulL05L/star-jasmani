<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->unsignedTinyInteger('parameter_number');        // 1, 2, 3, 4
            $table->string('label');                                // 'Parameter 1', 'Sesi Januari'
            $table->date('test_date')->nullable();                  // tanggal pelaksanaan tes
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['batch_id', 'parameter_number']);
            $table->index('batch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_parameters');
    }
};
