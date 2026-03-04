<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->string('household_number')->unique();
            $table->string('head_last_name');
            $table->string('head_first_name');
            $table->string('head_middle_name')->nullable();
            $table->string('sitio');
            $table->string('street')->nullable();
            $table->string('barangay')->default('Cogon');
            $table->string('city')->default('Ormoc City');
            $table->string('province')->default('Leyte');
            $table->integer('member_count')->default(1);
            $table->string('residency_type')->default('Permanent');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};