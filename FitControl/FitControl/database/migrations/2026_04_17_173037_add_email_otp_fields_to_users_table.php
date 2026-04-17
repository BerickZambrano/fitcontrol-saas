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
        Schema::table('users', function (Blueprint $table) {
            $table->string('two_factor_type')->default('none')->after('password');
            $table->string('two_factor_otp')->nullable()->after('two_factor_type');
            $table->timestamp('two_factor_otp_expires_at')->nullable()->after('two_factor_otp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['two_factor_type', 'two_factor_otp', 'two_factor_otp_expires_at']);
        });
    }
};
