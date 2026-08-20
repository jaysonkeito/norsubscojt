<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentProfileComplete
{
    /**
     * If a logged-in Intern or OJT Coordinator hasn't finished their profile,
     * lock them to their respective "Complete Your Profile" page — they can't
     * navigate anywhere else in the app until it's saved.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user
            && $user->isUnassigned()
            && !$request->routeIs('account-completion.show', 'account-completion.store', 'logout')
        ) {
            return redirect()->route('account-completion.show');
        }

        if (
            $user
            && $user->isPending()
            && !$request->routeIs('account-pending.show', 'logout')
        ) {
            return redirect()->route('account-pending.show');
        }

        if (
            $user
            && $user->isStudent()
            && $user->student
            && !$user->student->isProfileComplete()
            && !$request->routeIs('profile.complete', 'profile.complete.store', 'logout')
        ) {
            return redirect()->route('profile.complete');
        }

        if (
            $user
            && $user->isCoordinator()
            && $user->coordinatorProfile
            && !$user->coordinatorProfile->isProfileComplete()
            && !$request->routeIs('profile.show', 'profile.update', 'logout')
        ) {
            return redirect()->route('profile.show');
        }

        if (
            $user
            && $user->isCompany()
            && $user->companyProfile
            && !$user->companyProfile->isProfileComplete()
            && !$request->routeIs('profile.show', 'profile.update', 'logout')
        ) {
            return redirect()->route('profile.show');
        }

        return $next($request);
    }
}
