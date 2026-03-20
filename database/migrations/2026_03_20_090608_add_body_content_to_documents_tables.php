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
            $table->longText('body_content')->nullable()->after('purpose');
            $table->string('or_number')->nullable()->after('body_content');
            $table->decimal('amount', 10, 2)->nullable()->after('or_number');
        });

        Schema::table('clearances', function (Blueprint $table) {
            $table->longText('body_content')->nullable()->after('purpose');
            $table->string('or_number')->nullable()->after('body_content');
            $table->decimal('amount', 10, 2)->nullable()->after('or_number');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['body_content', 'or_number', 'amount']);
        });

        Schema::table('clearances', function (Blueprint $table) {
            $table->dropColumn(['body_content', 'or_number', 'amount']);
        });
    }
};
