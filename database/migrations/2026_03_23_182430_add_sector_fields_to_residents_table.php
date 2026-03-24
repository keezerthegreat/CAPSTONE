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
        Schema::table('residents', function (Blueprint $table) {
            $table->boolean('is_labor_force')->nullable()->default(false)->after('is_solo_parent');
            $table->boolean('is_unemployed')->nullable()->default(false)->after('is_labor_force');
            $table->boolean('is_ofw')->nullable()->default(false)->after('is_unemployed');
            $table->boolean('is_indigenous')->nullable()->default(false)->after('is_ofw');
            $table->boolean('is_out_of_school_child')->nullable()->default(false)->after('is_indigenous');
            $table->boolean('is_out_of_school_youth')->nullable()->default(false)->after('is_out_of_school_child');
            $table->boolean('is_student')->nullable()->default(false)->after('is_out_of_school_youth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn([
                'is_labor_force', 'is_unemployed', 'is_ofw', 'is_indigenous',
                'is_out_of_school_child', 'is_out_of_school_youth', 'is_student',
            ]);
        });
    }
};
