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
     * Google Sign-In is open to every role. $type is 'student' or
     * 'non_student', matching the same binary choice as manual registration.
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

        if (!$user) {
            $username = Str::slug($googleUser->getName()) . '-' . Str::lower(Str::random(4));

            $user = User::create([
                'name' => $googleUser->getName(),
                'username' => $username,
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(32)),
                // Same pattern as manual registration: Student is created
                // fully; Non-Student starts 'unassigned' and picks their
                // Designation on Account Completion right after logging in.
                'role' => $type === 'student' ? 'student' : 'unassigned',
                'status' => 'active',
            ]);

            if ($type === 'student') {
                Student::create(['user_id' => $user->id]);
            }
        }

        if ($user->isPending()) {
            return redirect()->route('login')->withErrors([
                'login' => 'Your account is still pending approval. Please check back later.',
            ]);
        }

        Auth::login($user);

        return (new AuthController())->postLoginRedirect($user);
    }
}
