<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Profile photo for roles without a dedicated profile table
     * (Admin, Dean). Students, Coordinators, and Company reps keep
     * their photos on their own profile tables.
     */
    public function up(): void
    {
        // Column may already exist if it was added manually — skip then.
        if (Schema::hasColumn('users', 'photo_path')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('photo_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });
    }
};
