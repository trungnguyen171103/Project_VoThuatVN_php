<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Make user_id nullable since students don't need accounts
            $table->foreignId('user_id')->nullable()->change();

            // Add direct student information fields
            $table->string('full_name')->after('user_id');
            $table->integer('birth_year')->after('full_name');
            $table->string('phone', 15)->after('birth_year');
            $table->date('registration_date')->after('address')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->dropColumn(['full_name', 'birth_year', 'phone', 'registration_date']);
        });
    }
};
