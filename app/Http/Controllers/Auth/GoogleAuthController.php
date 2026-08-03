<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CoordinatorProfile;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google. $type tells us what kind of account to create
     * if this Google user doesn't already have one here: 'student' or 'coordinator'.
     */
    public function redirect(Request $request, string $type)
    {
        abort_unless(in_array($type, ['student', 'coordinator']), 404);

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

        // Brand-new Coordinator via Google — show a confirmation step first,
        // since this is a role that requires Admin approval before it's usable.
        if ($type === 'coordinator') {
            $request->session()->put('google_pending_coordinator', [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
            ]);

            return redirect()->route('google.coordinator.confirm');
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

    public function showCoordinatorConfirm(Request $request)
    {
        $pending = $request->session()->get('google_pending_coordinator');

        if (!$pending) {
            return redirect()->route('login');
        }

        return view('auth.google-coordinator-confirm', ['email' => $pending['email']]);
    }

    public function confirmCoordinator(Request $request)
    {
        $pending = $request->session()->pull('google_pending_coordinator');

        abort_unless($pending, 403);

        $username = Str::slug($pending['name']) . '-' . Str::lower(Str::random(4));

        $user = User::create([
            'name' => $pending['name'],
            'username' => $username,
            'email' => $pending['email'],
            'password' => Hash::make(Str::random(32)),
            'role' => 'coordinator',
            'status' => 'pending',
        ]);

        CoordinatorProfile::create(['user_id' => $user->id]);

        return redirect()->route('login')->with(
            'success',
            'Your OJT Coordinator account has been submitted via Google and is pending approval from the System Admin.'
        );
    }

    private function logInExisting(User $user)
    {
        if ($user->isPending()) {
            return redirect()->route('login')->withErrors([
                'login' => 'Your account is still pending approval from the System Admin.',
            ]);
        }

        Auth::login($user);

        if ($user->isStudent() && $user->student && !$user->student->isProfileComplete()) {
            return redirect()->route('profile.complete');
        }

        if ($user->isCoordinator() && $user->coordinatorProfile && !$user->coordinatorProfile->isProfileComplete()) {
            return redirect()->route('coordinator-profile.complete');
        }

        return redirect()->intended(route('dashboard'));
    }
}
