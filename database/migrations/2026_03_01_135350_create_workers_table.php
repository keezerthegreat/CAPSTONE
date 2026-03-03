<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('workers', function (Blueprint $table) {
        $table->id();
        $table->string('full_name');
        $table->string('gender')->nullable();
        $table->date('birth_date')->nullable();
        $table->string('contact_number')->nullable();
        $table->string('address')->nullable();

        $table->string('position');
        $table->string('department')->nullable();
        $table->date('date_started')->nullable();
        $table->date('term_start')->nullable();
        $table->date('term_end')->nullable();

        $table->string('status')->default('Active');

        $table->timestamps();
    });
}
};
