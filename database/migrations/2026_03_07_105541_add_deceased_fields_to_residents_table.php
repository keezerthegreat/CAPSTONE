<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->boolean('is_deceased')->default(false)->after('is_voter');
            $table->date('date_of_death')->nullable()->after('is_deceased');
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['is_deceased', 'date_of_death']);
        });
    }
};