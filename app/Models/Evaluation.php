<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'evaluator_name', 'evaluation_date',
        'attendance_score', 'work_quality_score', 'attitude_score',
        'initiative_score', 'communication_score', 'total_score', 'comments',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
