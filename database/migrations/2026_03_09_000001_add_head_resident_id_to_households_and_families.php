<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->unsignedBigInteger('head_resident_id')->nullable()->after('household_number');
        });

        Schema::table('families', function (Blueprint $table) {
            $table->unsignedBigInteger('head_resident_id')->nullable()->after('family_name');
        });
    }

    public function down(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->dropColumn('head_resident_id');
        });

        Schema::table('families', function (Blueprint $table) {
            $table->dropColumn('head_resident_id');
        });
    }
};
