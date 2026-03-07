<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->string('family_name');
            $table->string('head_last_name');
            $table->string('head_first_name');
            $table->string('head_middle_name')->nullable();
            $table->foreignId('household_id')->nullable()->constrained('households')->onDelete('set null');
            $table->integer('member_count')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
