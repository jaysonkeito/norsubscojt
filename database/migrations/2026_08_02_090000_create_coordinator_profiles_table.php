<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coordinator_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('employee_id')->nullable();
            $table->string('prefix_title')->nullable();
            $table->string('suffix_title')->nullable();
            $table->string('institutional_email')->nullable();
            $table->string('gender')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->string('mobile_number')->nullable();
            $table->date('date_hired')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('resume_path')->nullable();
            $table->text('qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coordinator_profiles');
    }
};
