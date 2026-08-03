<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoordinatorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'employee_id', 'prefix_title', 'suffix_title',
        'institutional_email', 'gender', 'civil_status', 'department',
        'designation', 'mobile_number', 'date_hired', 'photo_path',
        'resume_path', 'qualification', 'specialization',
    ];

    protected function casts(): array
    {
        return [
            'date_hired' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A Coordinator profile counts as "complete" once the essentials
     * (Employee ID, Department, Designation) are filled in. Everything
     * else is optional.
     */
    public function isProfileComplete(): bool
    {
        return !empty($this->employee_id) && !empty($this->department) && !empty($this->designation);
    }
}
