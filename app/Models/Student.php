<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'student_id_no', 'course', 'year_level',
        'contact_number', 'address', 'company_id', 'coordinator_id',
        'required_hours', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }

    public function renderedHours(): float
    {
        return (float) $this->timeLogs()->where('status', 'approved')->sum('hours_rendered');
    }

    public function remainingHours(): float
    {
        return max(0, $this->required_hours - $this->renderedHours());
    }

    public function progressPercent(): int
    {
        if ($this->required_hours <= 0) return 0;
        return (int) min(100, round(($this->renderedHours() / $this->required_hours) * 100));
    }

    /**
     * Attendance summary: count of days logged, broken down by approval status.
     */
    public function attendanceSummary(): array
    {
        $counts = $this->timeLogs()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'total_days' => (int) $counts->sum(),
            'approved_days' => (int) ($counts['approved'] ?? 0),
            'pending_days' => (int) ($counts['pending'] ?? 0),
            'rejected_days' => (int) ($counts['rejected'] ?? 0),
        ];
    }
}
