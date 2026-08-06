<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Company;
use App\Models\CoordinatorProfile;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Suggested NORSU-BSC on-campus office names, shown as datalist options
     * on the Account Completion step. Users may also type a different
     * office name — this list is suggestions, not a hard enum.
     */
    public static function insideCampusOfficeSuggestions(): array
    {
        return [
            'MIS OFFICE',
            'LIBRARY',
            'ACCREDITATION',
            'CAS OFFICE',
            'CSIT FACULTY OFFICE',
        ];
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Everyone logs in with either their Email or their Username + password.
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

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->postLoginRedirect($user);
    }

    /**
     * Role-based redirection after any successful authentication (password
     * login or Google) — sends each role straight to its own dashboard, or
     * to whichever intermediate screen applies: Account Completion (still
     * unassigned), the pending-approval status page (designation picked,
     * awaiting Dean/Admin), or a profile-completion step.
     */
    public function postLoginRedirect(User $user)
    {
        if ($user->isUnassigned()) {
            return redirect()->route('account-completion.show');
        }

        if ($user->isPending()) {
            return redirect()->route('account-pending.show');
        }

        if ($user->isStudent() && $user->student && !$user->student->isProfileComplete()) {
            return redirect()->route('profile.complete');
        }

        if ($user->isCoordinator() && $user->coordinatorProfile && !$user->coordinatorProfile->isProfileComplete()) {
            return redirect()->route('coordinator-profile.complete');
        }

        if ($user->isCompany() && $user->companyProfile && !$user->companyProfile->isProfileComplete()) {
            return redirect()->route('company-profile.complete');
        }

        // Admin, Dean, and Company (and any Coordinator/Student already
        // complete) all land straight on their role-specific dashboard.
        return redirect()->intended(route('dashboard'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Registration — account basics only, no Designation or Office/Company
     * fields collected here.
     *
     * - Student: created active immediately, no approval needed.
     * - Non-Student: also created immediately, but with a transient
     *   role = 'unassigned' and status = 'active' — this lets them log in
     *   right away, at which point the profile-completion middleware locks
     *   them to the Account Completion screen to pick their real
     *   Designation. The account only becomes 'pending' (awaiting Dean/
     *   Admin approval) once that's submitted.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'account_type' => ['required', 'in:student,non_student'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
        ]);

        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        $user = User::create([
            'name' => $fullName,
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['account_type'] === 'student' ? 'student' : 'unassigned',
            'status' => 'active',
        ]);

        if ($validated['account_type'] === 'student') {
            Student::create(['user_id' => $user->id]);
        }

        return redirect()->route('login')->with(
            'success',
            'Account created! Please sign in to finish setting up your profile.'
        );
    }

    /**
     * Account Completion — shown to any logged-in user whose role is still
     * 'unassigned' (locked there by middleware). Lets them pick a
     * Designation and, for Office/Company, the conditional fields.
     */
    public function showAccountCompletion(Request $request)
    {
        abort_unless($request->user()->isUnassigned(), 403);

        return view('profile.account-completion', [
            'user' => $request->user(),
            'officeSuggestions' => self::insideCampusOfficeSuggestions(),
        ]);
    }

    public function showAccountPending(Request $request)
    {
        if (!$request->user()->isPending()) {
            return redirect()->route('dashboard');
        }

        return view('profile.account-pending', ['user' => $request->user()]);
    }

    public function storeAccountCompletion(Request $request)
    {
        $user = $request->user();
        abort_unless($user->isUnassigned(), 403);

        $rules = ['designation' => ['required', 'in:dean,coordinator,company']];

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

        $designation = $request->validate($rules);

        return $this->finalizeNonStudentAccount($user, $designation);
    }

    /**
     * Applies the chosen Designation to an existing 'unassigned' account —
     * updates its real role, sets up any related profile/company records,
     * flips it to 'pending', and logs the person out (they'll log back in
     * once approved). Shared by the manual Account Completion flow above
     * and the Google non-student path in GoogleAuthController.
     */
    public function finalizeNonStudentAccount(User $user, array $designation): \Illuminate\Http\RedirectResponse
    {
        $role = $designation['designation']; // 'dean' | 'coordinator' | 'company'

        $updateData = ['role' => $role, 'status' => 'pending'];

        if ($role === 'company') {
            $affiliationType = $designation['affiliation_type'];

            $companyName = $affiliationType === 'inside_campus'
                ? 'NORSU-BSC ' . trim($designation['office_name'])
                : trim($designation['company_name']);

            $company = $this->findOrCreateCompany($companyName, $affiliationType);

            $updateData['company_id'] = $company->id;
            $updateData['job_role'] = $designation['job_role'];
            $updateData['job_role_other'] = $designation['job_role'] === 'Others' ? ($designation['job_role_other'] ?? null) : null;
        }

        $user->update($updateData);

        if ($role === 'coordinator') {
            CoordinatorProfile::create(['user_id' => $user->id]);
        }

        if ($role === 'company') {
            CompanyProfile::create(['user_id' => $user->id]);
        }

        // They're 'pending' now — stay logged in, land on the pending-status page.
        $approver = $role === 'dean' ? 'the System Admin' : 'the Dean';
        $roleLabel = ['dean' => 'Dean', 'coordinator' => 'OJT Coordinator', 'company' => 'Office/Company'][$role];

        return redirect()->route('account-pending.show')->with(
            'success',
            "Your {$roleLabel} account has been submitted and is pending approval from {$approver}."
        );
    }

    /**
     * Matches an existing Company by name case-insensitively (so "MIS
     * OFFICE" and "Mis Office" reuse the same record instead of creating
     * near-duplicates), or creates a new one using the name exactly as typed.
     */
    private function findOrCreateCompany(string $name, string $affiliationType): Company
    {
        $existing = Company::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first();

        if ($existing) {
            return $existing;
        }

        return Company::create([
            'name' => $name,
            'affiliation_type' => $affiliationType,
            'moa_status' => 'pending',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
