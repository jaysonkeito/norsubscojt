<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Program;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    /**
     * List all colleges with their program counts.
     */
    public function index()
    {
        $colleges = College::ordered()
            ->withCount('programs')
            ->get();

        return view('colleges.index', compact('colleges'));
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

        return view('colleges.edit', compact('college'));
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
