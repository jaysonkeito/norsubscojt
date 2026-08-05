<?php

namespace App\Http\Controllers;

use App\Mail\AccountApprovedMail;
use App\Mail\AccountRejectedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        $this->sendApprovalEmail($pendingApproval);

        return redirect()->route('pending-approvals.index')->with(
            'success',
            "{$pendingApproval->name}'s account has been approved and can now log in."
        );
    }

    public function reject(Request $request, User $pendingApproval)
    {
        abort_unless($request->user()->canApprove($pendingApproval), 403);

        $name = $pendingApproval->name;
        $email = $pendingApproval->email;
        $roleLabel = $this->roleLabel($pendingApproval->role);
        $companyToCheck = $pendingApproval->role === 'company' ? $pendingApproval->company : null;

        $pendingApproval->delete();

        // Clean up the company record too, if it was created solely for this
        // registration and has no other approved users or interns attached.
        if ($companyToCheck && $companyToCheck->users()->count() === 0 && $companyToCheck->students()->count() === 0) {
            $companyToCheck->delete();
        }

        $this->sendRejectionEmail($email, $name, $roleLabel);

        return redirect()->route('pending-approvals.index')->with('success', "{$name}'s registration was rejected and removed.");
    }

    private function roleLabel(string $role): string
    {
        return ['dean' => 'Dean', 'coordinator' => 'OJT Coordinator', 'company' => 'Office/Company'][$role] ?? ucfirst($role);
    }

    /**
     * Email sends are wrapped in try/catch so a mail-server hiccup never
     * blocks the actual approval/rejection action from completing — the
     * account status change always succeeds even if the notification fails.
     */
    private function sendApprovalEmail(User $user): void
    {
        try {
            Mail::to($user->email)->send(new AccountApprovedMail($user->name, $this->roleLabel($user->role)));
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function sendRejectionEmail(string $email, string $name, string $roleLabel): void
    {
        try {
            Mail::to($email)->send(new AccountRejectedMail($name, $roleLabel));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
