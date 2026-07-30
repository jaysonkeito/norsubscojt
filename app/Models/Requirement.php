<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'document_name', 'file_path', 'status', 'submitted_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
