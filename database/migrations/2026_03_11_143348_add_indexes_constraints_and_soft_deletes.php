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
        Schema::table('residents', function (Blueprint $table) {
            if (! Schema::hasColumn('residents', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Add indexes only if they don't already exist (idempotent for partial migration recovery)
        $existingIndexes = array_column(
            DB::select("SELECT name FROM sqlite_master WHERE type='index' AND tbl_name='residents'"),
            'name'
        );
        Schema::table('residents', function (Blueprint $table) use ($existingIndexes) {
            if (! in_array('residents_status_index', $existingIndexes)) {
                $table->index('status');
            }
            if (! in_array('residents_last_name_index', $existingIndexes)) {
                $table->index('last_name');
            }
            if (! in_array('residents_household_id_index', $existingIndexes)) {
                $table->index('household_id');
            }
            if (! in_array('residents_family_id_index', $existingIndexes)) {
                $table->index('family_id');
            }
        });

        Schema::table('households', function (Blueprint $table) {
            if (! Schema::hasColumn('households', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('families', function (Blueprint $table) {
            if (! Schema::hasColumn('families', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->unique('certificate_no');
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['status']);
            $table->dropIndex(['last_name']);
            $table->dropIndex(['household_id']);
            $table->dropIndex(['family_id']);
        });

        Schema::table('households', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('families', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique(['certificate_no']);
        });
    }
};
