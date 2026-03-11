<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $existingIndexes = array_column(
            DB::select("SELECT name FROM sqlite_master WHERE type='index' AND tbl_name='households'"),
            'name'
        );

        Schema::table('households', function (Blueprint $table) use ($existingIndexes) {
            if (! in_array('households_household_number_unique', $existingIndexes)) {
                $table->unique('household_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->dropUnique(['household_number']);
        });
    }
};
