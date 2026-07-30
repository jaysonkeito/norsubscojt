<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Student;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        $evaluations = Evaluation::with('student.user')->latest('evaluation_date')->paginate(15);
        return view('evaluations.index', compact('evaluations'));
    }

    public function create()
    {
        $students = Student::with('user')->orderBy('id')->get();
        return view('evaluations.create', compact('students'));
    }

    public function store(Request $request)
    {
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

        $validated['total_score'] = $validated['attendance_score']
            + $validated['work_quality_score']
            + $validated['attitude_score']
            + $validated['initiative_score']
            + $validated['communication_score'];

        Evaluation::create($validated);

        return redirect()->route('evaluations.index')->with('success', 'Evaluation recorded.');
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();
        return back()->with('success', 'Evaluation removed.');
    }
}
