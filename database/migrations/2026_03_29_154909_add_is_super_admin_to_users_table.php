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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_super_admin')->default(false)->after('role');
            $table->boolean('is_archived')->default(false)->after('is_super_admin');
        });

        // Promote the first existing admin to super admin
        DB::table('users')
            ->where('role', 'admin')
            ->orderBy('id')
            ->limit(1)
            ->update(['is_super_admin' => true]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_super_admin', 'is_archived']);
        });
    }
};
