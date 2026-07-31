<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 'active' = can log in immediately. 'pending' = awaiting System Admin approval.
            // Students are always created as 'active' (no verification needed).
            // Self-registered Coordinator / Office-Company accounts start as 'pending'.
            $table->enum('status', ['active', 'pending'])->default('active')->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
