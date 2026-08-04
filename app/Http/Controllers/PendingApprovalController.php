<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PendingApprovalController extends Controller
{
    /**
     * Admin sees every pending account. Dean only sees Coordinator and
     * Office/Company registrations — pending Dean accounts are the System
     * Admin's call, not visible here for a Dean to act on.
     */
    public function index(Request $request)
    {
        $query = User::where('status', 'pending')->with('company');

        if ($request->user()->isDean()) {
            $query->whereIn('role', ['coordinator', 'company']);
        }

        $pendingUsers = $query->latest()->get();

        return view('pending-approvals.index', compact('pendingUsers'));
    }

    public function approve(Request $request, User $pendingApproval)
    {
        abort_unless($request->user()->canApprove($pendingApproval), 403);

        $pendingApproval->update(['status' => 'active']);

        // If this was a company registration, also mark the company's MOA as active
        if ($pendingApproval->role === 'company' && $pendingApproval->company) {
            $pendingApproval->company->update(['moa_status' => 'active']);
        }

        return redirect()->route('pending-approvals.index')->with(
            'success',
            "{$pendingApproval->name}'s account has been approved and can now log in."
        );
    }

    public function reject(Request $request, User $pendingApproval)
    {
        abort_unless($request->user()->canApprove($pendingApproval), 403);

        $name = $pendingApproval->name;
        $companyToCheck = $pendingApproval->role === 'company' ? $pendingApproval->company : null;

        $pendingApproval->delete();

        // Clean up the company record too, if it was created solely for this
        // registration and has no other approved users or interns attached.
        if ($companyToCheck && $companyToCheck->users()->count() === 0 && $companyToCheck->students()->count() === 0) {
            $companyToCheck->delete();
        }

        return redirect()->route('pending-approvals.index')->with('success', "{$name}'s registration was rejected and removed.");
    }
}
