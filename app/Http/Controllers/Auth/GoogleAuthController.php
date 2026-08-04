<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Google Sign-In is open to every role. $type is just 'student' or
     * 'non_student' — matching the same binary choice as manual
     * registration. A brand-new Non-Student pans out to a short onboarding
     * form (Designation + conditional fields) before anything is created.
     */
    public function redirect(Request $request, string $type)
    {
        abort_unless(in_array($type, ['student', 'non_student']), 404);

        $request->session()->put('google_login_type', $type);

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        $googleUser = Socialite::driver('google')->user();
        $type = $request->session()->pull('google_login_type', 'student');

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            return $this->logInExisting($user);
        }

        if ($type === 'non_student') {
            $request->session()->put('google_pending_profile', [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
            ]);

            return redirect()->route('google.onboarding');
        }

        // Student via Google — created active immediately with an
        // incomplete profile, same as a manually self-registered Intern.
        $username = Str::slug($googleUser->getName()) . '-' . Str::lower(Str::random(4));

        $user = User::create([
            'name' => $googleUser->getName(),
            'username' => $username,
            'email' => $googleUser->getEmail(),
            'password' => Hash::make(Str::random(32)),
            'role' => 'student',
            'status' => 'active',
        ]);

        Student::create(['user_id' => $user->id]);

        return $this->logInExisting($user);
    }

    /**
     * Onboarding form for a brand-new Non-Student Google sign-in: pick a
     * Designation (Dean / OJT Coordinator / Office-Company) plus whatever
     * conditional fields that designation needs — same shape as the second
     * half of the manual registration form.
     */
    public function showOnboarding(Request $request)
    {
        $pending = $request->session()->get('google_pending_profile');

        if (!$pending) {
            return redirect()->route('login');
        }

        return view('auth.google-onboarding', [
            'profile' => $pending,
            'officeSuggestions' => AuthController::insideCampusOfficeSuggestions(),
        ]);
    }

    public function storeOnboarding(Request $request, AuthController $authController)
    {
        $pending = $request->session()->get('google_pending_profile');
        abort_unless($pending, 403);

        $rules = [
            'designation' => ['required', 'in:dean,coordinator,company'],
        ];

        if ($request->input('designation') === 'company') {
            $rules['affiliation_type'] = ['required', 'in:inside_campus,outside_campus'];
            $rules['job_role'] = ['required', 'in:Manager,Supervisor,Others'];

            if ($request->input('job_role') === 'Others') {
                $rules['job_role_other'] = ['required', 'string', 'max:255'];
            }

            if ($request->input('affiliation_type') === 'inside_campus') {
                $rules['office_name'] = ['required', 'string', 'max:255'];
            } else {
                $rules['company_name'] = ['required', 'string', 'max:255'];
            }
        }

        $designation = Validator::make($request->all(), $rules)->validate();

        // Google already gave us name/email — a random password/username
        // stand in since this account only ever logs in via Google.
        $accountBasics = [
            'name' => $pending['name'],
            'username' => Str::slug($pending['name']) . '-' . Str::lower(Str::random(4)),
            'email' => $pending['email'],
            'password' => Hash::make(Str::random(32)),
        ];

        $request->session()->forget('google_pending_profile');

        return $authController->createNonStudentAccount($accountBasics, $designation);
    }

    private function logInExisting(User $user)
    {
        if ($user->isPending()) {
            return redirect()->route('login')->withErrors([
                'login' => 'Your account is still pending approval. Please check back later.',
            ]);
        }

        Auth::login($user);

        return (new AuthController())->postLoginRedirect($user);
    }
}
