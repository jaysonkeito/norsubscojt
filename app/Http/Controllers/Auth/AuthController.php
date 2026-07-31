<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Admin, Coordinator, and Company accounts log in with email + password.
     * Student (Intern) accounts log in with Student ID + password.
     * The login form has a single "login" field that accepts either.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($request->input('login'));
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL) !== false;

        if ($isEmail) {
            $user = User::where('email', $loginInput)->first();
        } else {
            // Treat input as a Student ID No. and resolve to the linked user account
            $student = Student::where('student_id_no', $loginInput)->first();
            $user = $student?->user;
        }

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors([
                'login' => 'The provided credentials do not match our records.',
            ])->onlyInput('login');
        }

        if ($user->isPending()) {
            return back()->withErrors([
                'login' => 'Your account is still pending approval from the System Admin. Please check back later.',
            ])->onlyInput('login');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Unified registration for Students, OJT Coordinators, and Office/Companies.
     * - Student accounts are created active immediately (no approval needed) and auto-logged in.
     * - Coordinator and Office/Company accounts are created as 'pending' and must be
     *   approved by the System Admin before they can log in.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'account_type' => ['required', 'in:student,coordinator,company'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'student_id_no' => ['required_if:account_type,student', 'nullable', 'string', 'unique:students,student_id_no'],
            'course' => ['required_if:account_type,student', 'nullable', 'string'],
            'year_level' => ['required_if:account_type,student', 'nullable', 'string'],
            'company_name' => ['required_if:account_type,company', 'nullable', 'string', 'max:255'],
        ]);

        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        if ($validated['account_type'] === 'student') {
            $user = User::create([
                'name' => $fullName,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
                'status' => 'active',
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_id_no' => $validated['student_id_no'],
                'course' => $validated['course'],
                'year_level' => $validated['year_level'],
            ]);

            Auth::login($user);

            return redirect()->route('dashboard');
        }

        if ($validated['account_type'] === 'coordinator') {
            User::create([
                'name' => $fullName,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'coordinator',
                'status' => 'pending',
            ]);

            return redirect()->route('login')->with(
                'success',
                'Your OJT Coordinator account has been submitted and is pending approval from the System Admin.'
            );
        }

        // Office / Company registration — creates the Company record too, both pending admin review
        $company = Company::create([
            'name' => $validated['company_name'],
            'moa_status' => 'pending',
        ]);

        User::create([
            'name' => $fullName,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'company',
            'status' => 'pending',
            'company_id' => $company->id,
        ]);

        return redirect()->route('login')->with(
            'success',
            'Your Office/Company account has been submitted and is pending approval from the System Admin.'
        );
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
