<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Student;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Evaluation::with('student.user');

        if ($user->isCoordinator()) {
            $query->whereHas('student', fn ($q) => $q->where('coordinator_id', $user->id));
        } elseif ($user->isCompany()) {
            $query->whereHas('student', fn ($q) => $q->where('company_id', $user->company_id));
        }
        // Admin and Dean see everyone — no extra scoping.

        $evaluations = $query->latest('evaluation_date')->paginate(15);
        return view('evaluations.index', compact('evaluations'));
    }

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

        return view('evaluations.create', compact('students'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'evaluator_name' => ['required', 'string'],
            'evaluation_date' => ['required', 'date'],
            'attendance_score' => ['required', 'integer', 'min:0', 'max:20'],
            'work_quality_score' => ['required', 'integer', 'min:0', 'max:20'],
            'attitude_score' => ['required', 'integer', 'min:0', 'max:20'],
            'initiative_score' => ['required', 'integer', 'min:0', 'max:20'],
            'communication_score' => ['required', 'integer', 'min:0', 'max:20'],
            'comments' => ['nullable', 'string'],
        ]);

        $student = Student::findOrFail($validated['student_id']);

        // A Coordinator/Company can only evaluate interns actually assigned to them
        if ($user->isCoordinator()) {
            abort_unless($student->coordinator_id === $user->id, 403);
        } elseif ($user->isCompany()) {
            abort_unless($student->company_id === $user->company_id, 403);
        }

        $validated['total_score'] = $validated['attendance_score']
            + $validated['work_quality_score']
            + $validated['attitude_score']
            + $validated['initiative_score']
            + $validated['communication_score'];

        Evaluation::create($validated);

        return redirect()->route('evaluations.index')->with('success', 'Evaluation recorded.');
    }

    public function destroy(Request $request, Evaluation $evaluation)
    {
        $user = $request->user();
        $evaluation->loadMissing('student');

        if ($user->isCoordinator()) {
            abort_unless($evaluation->student->coordinator_id === $user->id, 403);
        } elseif ($user->isCompany()) {
            abort_unless($evaluation->student->company_id === $user->company_id, 403);
        }
        // Admin and Dean can delete any evaluation.

        $evaluation->delete();
        return back()->with('success', 'Evaluation removed.');
    }
}
