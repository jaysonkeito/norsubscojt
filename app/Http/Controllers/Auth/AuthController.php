<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Company;
use App\Models\CoordinatorProfile;
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
     * Everyone logs in with either their Email or their Username + password.
     * (Admin-created Interns get username = their Student ID automatically,
     * so this one field covers both cases uniformly.)
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($request->input('login'));
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL) !== false;

        $user = $isEmail
            ? User::where('email', $loginInput)->first()
            : User::where('username', $loginInput)->first();

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

        // First login after registration: send Interns to finish their profile
        // (Student ID, Program, Year Level, etc.) before they see the dashboard.
        if ($user->isStudent() && $user->student && !$user->student->isProfileComplete()) {
            return redirect()->route('profile.complete');
        }

        if ($user->isCoordinator() && $user->coordinatorProfile && !$user->coordinatorProfile->isProfileComplete()) {
            return redirect()->route('coordinator-profile.complete');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Simplified registration for all three self-service roles.
     * - Intern: created active immediately, but with an *incomplete* Student
     *   profile — they finish the rest (Student ID, Program, Year Level, etc.)
     *   the first time they log in.
     * - Coordinator / Office-Company: created as 'pending', must be approved
     *   by the System Admin before they can log in at all.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'account_type' => ['required', 'in:student,coordinator,company'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company_name' => ['required_if:account_type,company', 'nullable', 'string', 'max:255'],
        ]);

        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        if ($validated['account_type'] === 'student') {
            $user = User::create([
                'name' => $fullName,
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
                'status' => 'active',
            ]);

            // Intentionally minimal — the rest is filled in via the
            // post-login "complete your profile" step.
            Student::create([
                'user_id' => $user->id,
            ]);

            return redirect()->route('login')->with(
                'success',
                'Account created! Please sign in to finish setting up your profile.'
            );
        }

        if ($validated['account_type'] === 'coordinator') {
            $user = User::create([
                'name' => $fullName,
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'coordinator',
                'status' => 'pending',
            ]);

            CoordinatorProfile::create(['user_id' => $user->id]);

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
            'username' => $validated['username'],
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
