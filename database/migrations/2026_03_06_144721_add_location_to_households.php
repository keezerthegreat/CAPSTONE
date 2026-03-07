<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('households', function (Blueprint $table) {
            if (!Schema::hasColumn('households', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('member_count');
            }
            if (!Schema::hasColumn('households', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('households', function (Blueprint $table) {
            if (Schema::hasColumn('households', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('households', 'longitude')) {
                $table->dropColumn('longitude');
            }
        });
    }
};