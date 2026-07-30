<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'log_date', 'time_in', 'time_out',
        'tasks_performed', 'hours_rendered', 'status', 'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public static function computeHours(?string $timeIn, ?string $timeOut): float
    {
        if (!$timeIn || !$timeOut) return 0;
        $in = Carbon::parse($timeIn);
        $out = Carbon::parse($timeOut);
        if ($out->lessThan($in)) return 0;
        return round($in->diffInMinutes($out) / 60, 2);
    }
}
