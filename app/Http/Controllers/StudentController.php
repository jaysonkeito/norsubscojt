<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'company', 'coordinator'])->latest()->paginate(15);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get();
        $coordinators = User::where('role', 'coordinator')->orderBy('name')->get();
        return view('students.create', compact('companies', 'coordinators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:4'],
            'student_id_no' => ['required', 'unique:students,student_id_no'],
            'course' => ['required', 'string'],
            'year_level' => ['required', 'string'],
            'contact_number' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'coordinator_id' => ['nullable', 'exists:users,id'],
            'required_hours' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:not_deployed,deployed,completed'],
        ]);

        // Interns log in with Student ID, not email — email is optional/for records only.
        // If left blank, generate a unique placeholder so the users.email column (unique) is satisfied.
        $email = $validated['email'] ?? ($validated['student_id_no'] . '@intern.norsu.local');

        // Default password = last name (lowercased, spaces removed), if the admin left it blank.
        $nameParts = preg_split('/\s+/', trim($validated['name']));
        $lastName = strtolower(str_replace(' ', '', end($nameParts)));
        $password = $validated['password'] ?: $lastName;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user->id,
            'student_id_no' => $validated['student_id_no'],
            'course' => $validated['course'],
            'year_level' => $validated['year_level'],
            'contact_number' => $validated['contact_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'company_id' => $validated['company_id'] ?? null,
            'coordinator_id' => $validated['coordinator_id'] ?? null,
            'required_hours' => $validated['required_hours'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('students.index')->with(
            'success',
            "Student created. Login credentials — Student ID: {$validated['student_id_no']} | Password: {$password}"
        );
    }

    public function show(Student $student)
    {
        $student->load(['user', 'company', 'coordinator', 'timeLogs', 'evaluations', 'requirements']);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $companies = Company::orderBy('name')->get();
        $coordinators = User::where('role', 'coordinator')->orderBy('name')->get();
        return view('students.edit', compact('student', 'companies', 'coordinators'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'course' => ['required', 'string'],
            'year_level' => ['required', 'string'],
            'contact_number' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'coordinator_id' => ['nullable', 'exists:users,id'],
            'required_hours' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:not_deployed,deployed,completed'],
        ]);

        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->user()->delete();
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student removed.');
    }
}
