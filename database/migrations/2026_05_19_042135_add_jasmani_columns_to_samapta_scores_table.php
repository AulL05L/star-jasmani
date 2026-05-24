<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('samapta_scores', function (Blueprint $table) {
            $table->decimal('nilai_jasmani_a', 5, 2)->nullable()->after('score_renang');
            $table->decimal('nilai_jasmani_b', 5, 2)->nullable()->after('nilai_jasmani_a');
            $table->decimal('nilai_total_jasmani', 5, 2)->nullable()->after('nilai_jasmani_b');
            $table->decimal('snapshot_ukg_weight', 3, 2)->nullable()->after('nilai_total_jasmani');
            $table->decimal('snapshot_renang_weight', 3, 2)->nullable()->after('snapshot_ukg_weight');
        });
    }

    public function down(): void
    {
        Schema::table('samapta_scores', function (Blueprint $table) {
            $table->dropColumn([
                'nilai_jasmani_a',
                'nilai_jasmani_b',
                'nilai_total_jasmani',
                'snapshot_ukg_weight',
                'snapshot_renang_weight',
            ]);
        });
    }
};