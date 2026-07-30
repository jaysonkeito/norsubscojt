<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isStudent()) {
            $requirements = $user->student
                ? $user->student->requirements()->latest()->paginate(15)
                : collect();
        } else {
            $requirements = Requirement::with('student.user')->latest()->paginate(15);
        }

        return view('requirements.index', compact('requirements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_name' => ['required', 'string'],
            'file' => ['nullable', 'file', 'max:5120'],
        ]);

        $student = $request->user()->student;
        abort_if(!$student, 403, 'No student profile linked to this account.');

        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('requirements', 'public');
        }

        Requirement::create([
            'student_id' => $student->id,
            'document_name' => $validated['document_name'],
            'file_path' => $path,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Requirement submitted.');
    }

    public function updateStatus(Request $request, Requirement $requirement)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        $requirement->update($validated);

        return back()->with('success', 'Requirement status updated.');
    }
}
