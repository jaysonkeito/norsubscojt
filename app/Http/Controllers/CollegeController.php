<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    /**
     * List all colleges with their program counts and total student counts.
     */
    public function index()
    {
        $programNamesByCollege = College::ordered()
            ->with('programs')
            ->get()
            ->mapWithKeys(fn($college) => [
                $college->id => $college->programs->pluck('name'),
            ]);

        $colleges = College::ordered()->withCount('programs')->get();

        // Count students per college by matching students.course against program names
        $studentCounts = [];
        foreach ($programNamesByCollege as $collegeId => $programNames) {
            $studentCounts[$collegeId] = $programNames->isNotEmpty()
                ? Student::whereIn('course', $programNames)->count()
                : 0;
        }

        return view('colleges.index', compact('colleges', 'studentCounts'));
    }

    /**
     * Display a college's programs with the interns enrolled in each.
     */
    public function show(College $college)
    {
        $college->load('programs');
        $college->loadCount('programs');

        $programNames = $college->programs->pluck('name');

        // Group students by their course (program name)
        $studentsByProgram = collect();
        if ($programNames->isNotEmpty()) {
            $studentsByProgram = Student::whereIn('course', $programNames)
                ->with('user')
                ->get()
                ->groupBy('course');
        }

        $programCounts = $studentsByProgram->map(fn($group) => $group->count());

        return view('colleges.show', compact('college', 'studentsByProgram', 'programCounts'));
    }

    /**
     * Show form to create a new college.
     */
    public function create()
    {
        return view('colleges.create');
    }

    /**
     * Store a new college.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:colleges,name'],
        ]);

        College::create($validated);

        return redirect()->route('colleges.index')->with('success', 'College added successfully.');
    }

    /**
     * Show form to edit a college and manage its programs.
     */
    public function edit(College $college)
    {
        $college->load('programs');

        // Count students per program within this college
        $programNames = $college->programs->pluck('name');
        $studentCounts = collect();
        if ($programNames->isNotEmpty()) {
            $studentCounts = Student::whereIn('course', $programNames)
                ->selectRaw('course, count(*) as total')
                ->groupBy('course')
                ->pluck('total', 'course');
        }

        return view('colleges.edit', compact('college', 'studentCounts'));
    }

    /**
     * Update a college's name.
     */
    public function update(Request $request, College $college)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:colleges,name,' . $college->id],
        ]);

        $college->update($validated);

        return redirect()->route('colleges.index')->with('success', 'College updated successfully.');
    }

    /**
     * Delete a college and its programs.
     */
    public function destroy(College $college)
    {
        $college->delete();

        return redirect()->route('colleges.index')->with('success', 'College and its programs removed.');
    }

    // ─── Program management (nested under a college) ────────────────

    /**
     * Add a program to a college.
     */
    public function storeProgram(Request $request, College $college)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Prevent duplicate program names within the same college
        $exists = Program::where('college_id', $college->id)
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'This program already exists in this college.']);
        }

        Program::create([
            'college_id' => $college->id,
            'name' => $validated['name'],
        ]);

        return redirect()->route('colleges.edit', $college)->with('success', 'Program added.');
    }

    /**
     * Remove a program.
     */
    public function destroyProgram(College $college, Program $program)
    {
        abort_unless($program->college_id === $college->id, 404);

        $program->delete();

        return redirect()->route('colleges.edit', $college)->with('success', 'Program removed.');
    }
}
