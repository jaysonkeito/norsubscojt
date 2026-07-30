<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('evaluator_name');
            $table->date('evaluation_date');
            $table->unsignedTinyInteger('attendance_score')->default(0);
            $table->unsignedTinyInteger('work_quality_score')->default(0);
            $table->unsignedTinyInteger('attitude_score')->default(0);
            $table->unsignedTinyInteger('initiative_score')->default(0);
            $table->unsignedTinyInteger('communication_score')->default(0);
            $table->unsignedSmallInteger('total_score')->default(0);
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
