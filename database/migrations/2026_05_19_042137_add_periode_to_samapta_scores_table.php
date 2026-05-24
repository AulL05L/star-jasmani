<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('samapta_scores', function (Blueprint $table) {
            $table->unsignedTinyInteger('parameter_ke')->default(1)->after('session_label');
            $table->year('tahun_sesi')->nullable()->after('parameter_ke');
            $table->foreignId('institution_id')
                  ->nullable()
                  ->after('athlete_id')
                  ->constrained('institutions')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('samapta_scores', function (Blueprint $table) {
            $table->dropForeign(['institution_id']);
            $table->dropColumn(['parameter_ke', 'tahun_sesi', 'institution_id']);
        });
    }
};