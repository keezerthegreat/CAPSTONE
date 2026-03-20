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
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('civil_status')->nullable()->after('resident_name');
            $table->string('purok')->nullable()->after('civil_status');
            $table->string('requestor')->nullable()->after('purok');
        });

        Schema::table('clearances', function (Blueprint $table) {
            $table->string('civil_status')->nullable()->after('resident_name');
            $table->string('purok')->nullable()->after('civil_status');
            $table->string('requestor')->nullable()->after('purok');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['civil_status', 'purok', 'requestor']);
        });

        Schema::table('clearances', function (Blueprint $table) {
            $table->dropColumn(['civil_status', 'purok', 'requestor']);
        });
    }
};
