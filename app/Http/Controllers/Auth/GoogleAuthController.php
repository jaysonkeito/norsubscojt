<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        if (!$user) {
            $username = Str::slug($googleUser->getName()) . '-' . Str::lower(Str::random(4));

            if ($type === 'coordinator') {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'username' => $username,
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(32)),
                    'role' => 'coordinator',
                    'status' => 'pending',
                ]);

                return redirect()->route('login')->with(
                    'success',
                    'Your OJT Coordinator account has been submitted via Google and is pending approval from the System Admin.'
                );
            }

            // Student via Google — created active immediately with an
            // incomplete profile, same as a manually self-registered Intern.
            $user = User::create([
                'name' => $googleUser->getName(),
                'username' => $username,
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(32)),
                'role' => 'student',
                'status' => 'active',
            ]);

            Student::create(['user_id' => $user->id]);
        }

        if ($user->isPending()) {
            return redirect()->route('login')->withErrors([
                'login' => 'Your account is still pending approval from the System Admin.',
            ]);
        }

        Auth::login($user);

        if ($user->isStudent() && $user->student && !$user->student->isProfileComplete()) {
            return redirect()->route('profile.complete');
        }

        return redirect()->intended(route('dashboard'));
    }
}
