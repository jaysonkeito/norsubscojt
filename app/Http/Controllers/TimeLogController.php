<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TimeLogController extends Controller
{
    /**
     * Interns are view-only here — Admin/Coordinator/Company record time on
     * their behalf. Everyone can filter by Day / Week / Month / a custom
     * Range and see a total-hours summary for that period.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $period = $request->input('period', 'month');

        [$rangeStart, $rangeEnd] = $this->resolveRange($period, $request->input('start'), $request->input('end'));

        $query = TimeLog::with('student.user')->whereBetween('log_date', [$rangeStart, $rangeEnd]);

        if ($user->isStudent()) {
            $query->whereHas('student', fn ($q) => $q->where('user_id', $user->id));
        } elseif ($user->isCoordinator()) {
            $query->whereHas('student', fn ($q) => $q->where('coordinator_id', $user->id));
        } elseif ($user->isCompany()) {
            $query->whereHas('student', fn ($q) => $q->where('company_id', $user->company_id));
        }
        // Admin sees everyone in range — no extra scoping.

        $logs = $query->latest('log_date')->paginate(15)->withQueryString();

        $totalHours = (clone $query)->where('status', 'approved')->sum('hours_rendered');

        return view('timelogs.index', [
            'logs' => $logs,
            'period' => $period,
            'rangeStart' => $rangeStart,
            'rangeEnd' => $rangeEnd,
            'totalHours' => $totalHours,
        ]);
    }

    private function resolveRange(string $period, ?string $start, ?string $end): array
    {
        $today = Carbon::today();

        return match ($period) {
            'day' => [$today->copy()->startOfDay(), $today->copy()->endOfDay()],
            'week' => [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()],
            'range' => [
                $start ? Carbon::parse($start)->startOfDay() : $today->copy()->startOfMonth(),
                $end ? Carbon::parse($end)->endOfDay() : $today->copy()->endOfDay(),
            ],
            default => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()], // 'month'
        };
    }

    /**
     * Only Admin, Coordinator, or Company reps reach this — they pick which
     * Intern the entry is for, scoped to who they're allowed to log for.
     */
    public function create(Request $request)
    {
        $user = $request->user();

        $studentsQuery = Student::with('user');
        if ($user->isCoordinator()) {
            $studentsQuery->where('coordinator_id', $user->id);
        } elseif ($user->isCompany()) {
            $studentsQuery->where('company_id', $user->company_id);
        }
        $students = $studentsQuery->orderBy('id')->get();

        return view('timelogs.create', compact('students'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'log_date' => ['required', 'date'],
            'time_in' => ['required'],
            'time_out' => ['required'],
            'tasks_performed' => ['nullable', 'string'],
        ]);

        $student = Student::findOrFail($validated['student_id']);

        // A Coordinator/Company can only log time for interns actually assigned to them
        if ($user->isCoordinator()) {
            abort_unless($student->coordinator_id === $user->id, 403);
        } elseif ($user->isCompany()) {
            abort_unless($student->company_id === $user->company_id, 403);
        }

        TimeLog::create([
            'student_id' => $student->id,
            'log_date' => $validated['log_date'],
            'time_in' => $validated['time_in'],
            'time_out' => $validated['time_out'],
            'tasks_performed' => $validated['tasks_performed'] ?? null,
            'hours_rendered' => TimeLog::computeHours($validated['time_in'], $validated['time_out']),
            // Recorded directly by an authorized party — approved immediately.
            'status' => 'approved',
        ]);

        return redirect()->route('timelogs.index')->with('success', 'Time log recorded.');
    }

    public function updateStatus(Request $request, TimeLog $timeLog)
    {
        $user = $request->user();

        if ($user->isCompany()) {
            abort_unless($timeLog->student->company_id === $user->company_id, 403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'remarks' => ['nullable', 'string'],
        ]);

        $timeLog->update($validated);

        return back()->with('success', 'Time log updated.');
    }

    public function destroy(TimeLog $timeLog)
    {
        $timeLog->delete();
        return back()->with('success', 'Time log deleted.');
    }
}
