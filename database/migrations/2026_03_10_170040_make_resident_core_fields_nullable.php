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
            $table->string('gender')->nullable()->change();
            $table->date('birthdate')->nullable()->change();
            $table->integer('age')->nullable()->change();
            $table->string('province')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('barangay')->nullable()->change();
            $table->text('address')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->string('gender')->nullable(false)->change();
            $table->date('birthdate')->nullable(false)->change();
            $table->integer('age')->nullable(false)->change();
            $table->string('province')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('barangay')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
        });
    }
};
