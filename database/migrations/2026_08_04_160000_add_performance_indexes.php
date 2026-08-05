<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('status');
            $table->index('role');
        });

        Schema::table('time_logs', function (Blueprint $table) {
            $table->index('log_date');
            $table->index('status');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['role']);
        });

        Schema::table('time_logs', function (Blueprint $table) {
            $table->dropIndex(['log_date']);
            $table->dropIndex(['status']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
