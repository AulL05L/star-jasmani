<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('samapta_scores', function (Blueprint $table) {
            $table->foreignId('parameter_id')
                  ->nullable()
                  ->after('athlete_id')
                  ->constrained('batch_parameters')
                  ->nullOnDelete();

            $table->index('parameter_id');
        });
    }

    public function down(): void
    {
        Schema::table('samapta_scores', function (Blueprint $table) {
            $table->dropForeign(['parameter_id']);
            $table->dropIndex(['parameter_id']);
            $table->dropColumn('parameter_id');
        });
    }
};
