<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            // null = semua parameter boleh diakses (default)
            // [1,2,3,4] = hanya parameter tertentu yang bisa dilihat member
            $table->json('allowed_parameters')->nullable()->after('batch_id');
        });
    }

    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropColumn('allowed_parameters');
        });
    }
};
