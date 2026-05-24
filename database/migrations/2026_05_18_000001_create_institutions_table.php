<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();       // 'POLRI', 'TNI-AD', 'TNI-AL', 'TNI-AU'
            $table->string('name');                      // 'Kepolisian Negara Republik Indonesia'
            $table->decimal('ukg_weight', 5, 2)->default(80.00);    // bobot kemampuan jasmani (%)
            $table->decimal('renang_weight', 5, 2)->default(20.00); // bobot renang (%)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
