<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('households', function (Blueprint $table) {

            // 📍 Location coordinates for map pin
            $table->decimal('latitude', 10, 7)->nullable()->after('member_count');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('households', function (Blueprint $table) {

            $table->dropColumn('latitude');
            $table->dropColumn('longitude');

        });
    }
};