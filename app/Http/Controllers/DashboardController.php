<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Company;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $data = [
                'totalStudents' => Student::count(),
                'deployedCount' => Student::where('status', 'deployed')->count(),
                'completedCount' => Student::where('status', 'completed')->count(),
                'totalCompanies' => Company::count(),
                'totalCoordinators' => User::where('role', 'coordinator')->count(),
                'announcements' => Announcement::latest()->take(5)->get(),
            ];
            return view('dashboard.admin', $data);
        }

        if ($user->isDean()) {
            $data = [
                'totalStudents' => Student::count(),
                'deployedCount' => Student::where('status', 'deployed')->count(),
                'completedCount' => Student::where('status', 'completed')->count(),
                'totalCompanies' => Company::count(),
                'totalCoordinators' => User::where('role', 'coordinator')->count(),
                'pendingApprovals' => User::where('status', 'pending')->whereIn('role', ['coordinator', 'company'])->count(),
                'announcements' => Announcement::latest()->take(5)->get(),
            ];
            return view('dashboard.dean', $data);
        }

        if ($user->isCoordinator()) {
            $students = Student::where('coordinator_id', $user->id)->with('company')->get();
            $data = [
                'students' => $students,
                'announcements' => Announcement::latest()->take(5)->get(),
            ];
            return view('dashboard.coordinator', $data);
        }

        if ($user->isCompany()) {
            $students = $user->company_id
                ? Student::where('company_id', $user->company_id)->with('user')->get()
                : collect();
            $data = [
                'company' => $user->company,
                'students' => $students,
                'announcements' => Announcement::latest()->take(5)->get(),
            ];
            return view('dashboard.company', $data);
        }

        // student
        $student = $user->student;
        $data = [
            'student' => $student,
            'recentLogs' => $student ? $student->timeLogs()->latest('log_date')->take(5)->get() : collect(),
            'announcements' => Announcement::latest()->take(5)->get(),
        ];
        return view('dashboard.student', $data);
    }
}
