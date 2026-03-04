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

        // Personal Info
        $table->string('first_name');
        $table->string('last_name');
        $table->string('middle_name')->nullable();
        $table->date('birthdate')->nullable();
        $table->string('gender')->nullable();
        $table->string('civil_status')->nullable();

        // Contact Info
        $table->string('address')->nullable();
        $table->string('contact_number')->nullable();
        $table->string('email')->nullable();

        // Barangay Details
        $table->string('position');
        $table->date('date_hired')->nullable();
        $table->string('employment_status')->nullable();

        $table->timestamps();
    });
}
};
