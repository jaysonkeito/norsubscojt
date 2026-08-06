<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 'unassigned' is a transient placeholder role: a Non-Student account
        // sits here from the moment it's created until the person logs in and
        // picks their real Designation (Dean/Coordinator/Company) on the
        // Account Completion page. It should never be a role anyone keeps.
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','coordinator','student','company','dean','unassigned') NOT NULL DEFAULT 'student'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','coordinator','student','company','dean') NOT NULL DEFAULT 'student'");
    }
};
