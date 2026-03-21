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
            $table->string('purpose')->nullable()->change();
        });

        Schema::table('clearances', function (Blueprint $table) {
            $table->string('purpose')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('purpose')->nullable(false)->change();
        });

        Schema::table('clearances', function (Blueprint $table) {
            $table->string('purpose')->nullable(false)->change();
        });
    }
};
