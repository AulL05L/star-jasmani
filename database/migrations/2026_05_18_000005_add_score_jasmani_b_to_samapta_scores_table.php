<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('samapta_scores', function (Blueprint $table) {
            // Jasmani B = rata-rata (pushup + situp + pullup + shuttle)
            // Diperlukan untuk formula yang benar sesuai standar POLRI
            $table->decimal('score_jasmani_b', 5, 2)->nullable()->after('score_shuttle');
        });
    }

    public function down(): void
    {
        Schema::table('samapta_scores', function (Blueprint $table) {
            $table->dropColumn('score_jasmani_b');
        });
    }
};
