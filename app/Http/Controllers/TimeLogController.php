<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isStudent()) {
            $logs = $user->student
                ? $user->student->timeLogs()->latest('log_date')->paginate(15)
                : collect();
        } elseif ($user->isCoordinator()) {
            $logs = TimeLog::whereHas('student', fn ($q) => $q->where('coordinator_id', $user->id))
                ->with('student.user')->latest('log_date')->paginate(15);
        } elseif ($user->isCompany()) {
            $logs = TimeLog::whereHas('student', fn ($q) => $q->where('company_id', $user->company_id))
                ->with('student.user')->latest('log_date')->paginate(15);
        } else {
            $logs = TimeLog::with('student.user')->latest('log_date')->paginate(15);
        }

        return view('timelogs.index', compact('logs'));
    }

    public function create(Request $request)
    {
        return view('timelogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'log_date' => ['required', 'date'],
            'time_in' => ['required'],
            'time_out' => ['required'],
            'tasks_performed' => ['nullable', 'string'],
        ]);

        $student = $request->user()->student;
        abort_if(!$student, 403, 'No student profile linked to this account.');

        TimeLog::create([
            'student_id' => $student->id,
            'log_date' => $validated['log_date'],
            'time_in' => $validated['time_in'],
            'time_out' => $validated['time_out'],
            'tasks_performed' => $validated['tasks_performed'] ?? null,
            'hours_rendered' => TimeLog::computeHours($validated['time_in'], $validated['time_out']),
            'status' => 'pending',
        ]);

        return redirect()->route('timelogs.index')->with('success', 'Time log submitted for approval.');
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
