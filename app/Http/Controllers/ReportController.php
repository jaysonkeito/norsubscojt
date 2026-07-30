<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with(['user', 'company'])->get()->map(function ($student) {
            return [
                'name' => $student->user->name,
                'student_id_no' => $student->student_id_no,
                'company' => $student->company->name ?? '—',
                'required_hours' => $student->required_hours,
                'rendered_hours' => $student->renderedHours(),
                'remaining_hours' => $student->remainingHours(),
                'progress' => $student->progressPercent(),
                'status' => $student->status,
            ];
        });

        return view('reports.index', compact('students'));
    }
}
