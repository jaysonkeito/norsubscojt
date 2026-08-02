<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Loosen these to nullable so a self-registered Intern can be created
        // right away with an incomplete profile, then fill the rest in later.
        // Using raw SQL (not ->nullable()->change()) to avoid requiring doctrine/dbal.
        DB::statement('ALTER TABLE students MODIFY COLUMN student_id_no VARCHAR(255) NULL');
        DB::statement('ALTER TABLE students MODIFY COLUMN course VARCHAR(255) NULL');
        DB::statement('ALTER TABLE students MODIFY COLUMN year_level VARCHAR(255) NULL');

        Schema::table('students', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('address');
            $table->date('birthdate')->nullable()->after('gender');
            $table->string('phone_number')->nullable()->after('birthdate');
            $table->string('guardian_name')->nullable()->after('phone_number');
            $table->string('guardian_contact')->nullable()->after('guardian_name');
            $table->string('facebook_link')->nullable()->after('guardian_contact');
            $table->string('youtube_link')->nullable()->after('facebook_link');
            $table->string('linkedin_link')->nullable()->after('youtube_link');
            $table->string('photo_path')->nullable()->after('linkedin_link');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'gender', 'birthdate', 'phone_number', 'guardian_name',
                'guardian_contact', 'facebook_link', 'youtube_link',
                'linkedin_link', 'photo_path',
            ]);
        });

        DB::statement('ALTER TABLE students MODIFY COLUMN student_id_no VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE students MODIFY COLUMN course VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE students MODIFY COLUMN year_level VARCHAR(255) NOT NULL');
    }
};
