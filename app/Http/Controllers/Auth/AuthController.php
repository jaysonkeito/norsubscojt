<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
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

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'student_id_no' => ['required', 'string', 'unique:students'],
            'course' => ['required', 'string'],
            'year_level' => ['required', 'string'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
