<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Expand the role enum to include 'dean'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','coordinator','student','company','dean') NOT NULL DEFAULT 'student'");

        Schema::table('users', function (Blueprint $table) {
            // Only populated for role='company' — describes the registering
            // person's job title at that office, not the office itself.
            $table->string('job_role')->nullable()->after('company_id');
            $table->string('job_role_other')->nullable()->after('job_role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['job_role', 'job_role_other']);
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','coordinator','student','company') NOT NULL DEFAULT 'student'");
    }
};
