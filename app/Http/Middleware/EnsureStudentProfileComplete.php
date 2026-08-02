<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentProfileComplete
{
    /**
     * If a logged-in Intern hasn't finished their profile (Student ID, Program,
     * Year Level), lock them to the "Complete Your Profile" page — they can't
     * navigate anywhere else in the app until it's saved.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user
            && $user->isStudent()
            && $user->student
            && !$user->student->isProfileComplete()
            && !$request->routeIs('profile.complete', 'profile.complete.store', 'logout')
        ) {
            return redirect()->route('profile.complete');
        }

        return $next($request);
    }
}
