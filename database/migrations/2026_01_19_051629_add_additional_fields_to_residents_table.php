<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->string('civil_status')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();

            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();

            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->string('education_level')->nullable();

            $table->boolean('is_senior')->nullable();
            $table->boolean('is_pwd')->nullable();
            $table->boolean('is_voter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn([
                'civil_status',
                'nationality',
                'religion',
                'contact_number',
                'email',
                'occupation',
                'employer',
                'monthly_income',
                'education_level',
                'is_senior',
                'is_pwd',
                'is_voter',
            ]);
        });
    }
};
