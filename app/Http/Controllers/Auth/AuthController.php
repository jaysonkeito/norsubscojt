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
use Illuminate\Support\Facades\Validator;
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

        if ($user->isPending()) {
            return back()->withErrors([
                'login' => 'Your account is still pending approval. Please check back later.',
            ])->onlyInput('login');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->postLoginRedirect($user);
    }

    /**
     * Role-based redirection after any successful authentication (password
     * login or Google) — sends each role straight to its own dashboard, or
     * to a profile-completion step first if one is required and not yet done.
     */
    public function postLoginRedirect(User $user)
    {
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
     * STEP 1 of registration — account basics only. No Designation or
     * Office/Company fields are collected here at all.
     *
     * - Student: created active immediately, no approval needed.
     * - Non-Student: nothing is created yet. The basics are stashed in the
     *   session and the person is sent to a separate Account Completion
     *   screen to pick their Designation first.
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

        if ($validated['account_type'] === 'student') {
            $user = User::create([
                'name' => $fullName,
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
                'status' => 'active',
            ]);

            Student::create(['user_id' => $user->id]);

            return redirect()->route('login')->with(
                'success',
                'Account created! Please sign in to finish setting up your profile.'
            );
        }

        // Non-Student — stash account basics, defer creation to Step 2.
        $request->session()->put('pending_account_basics', [
            'name' => $fullName,
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('account-completion.show');
    }

    /**
     * STEP 2 of registration (Non-Student only) — "Account Completion."
     * Shown right after Step 1, before the account exists at all. Requires
     * the session stash from Step 1; if it's missing (e.g. direct URL
     * visit, or the session expired), bounce back to the start.
     */
    public function showAccountCompletion(Request $request)
    {
        $basics = $request->session()->get('pending_account_basics');

        if (!$basics) {
            return redirect()->route('register');
        }

        return view('auth.account-completion', [
            'basics' => $basics,
            'officeSuggestions' => self::insideCampusOfficeSuggestions(),
        ]);
    }

    public function storeAccountCompletion(Request $request)
    {
        $basics = $request->session()->get('pending_account_basics');
        abort_unless($basics, 403);

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

        $request->session()->forget('pending_account_basics');

        return $this->createNonStudentAccount($basics, $designation);
    }

    /**
     * Shared by both entry points into "Account Completion" — the manual
     * two-step registration above, and the Google non-student onboarding
     * form in GoogleAuthController. $accountBasics has name/username/email/
     * password (already hashed); $designation has the picked designation
     * plus any conditional Office/Company fields.
     */
    public function createNonStudentAccount(array $accountBasics, array $designation)
    {
        $role = $designation['designation']; // 'dean' | 'coordinator' | 'company'

        $userData = [
            'name' => $accountBasics['name'],
            'username' => $accountBasics['username'],
            'email' => $accountBasics['email'],
            'password' => $accountBasics['password'],
            'role' => $role,
            'status' => 'pending',
        ];

        if ($role === 'company') {
            $affiliationType = $designation['affiliation_type'];

            $companyName = $affiliationType === 'inside_campus'
                ? 'NORSU-BSC ' . trim($designation['office_name'])
                : trim($designation['company_name']);

            $company = $this->findOrCreateCompany($companyName, $affiliationType);

            $userData['company_id'] = $company->id;
            $userData['job_role'] = $designation['job_role'];
            $userData['job_role_other'] = $designation['job_role'] === 'Others' ? ($designation['job_role_other'] ?? null) : null;
        }

        $user = User::create($userData);

        if ($role === 'coordinator') {
            CoordinatorProfile::create(['user_id' => $user->id]);
        }

        if ($role === 'company') {
            CompanyProfile::create(['user_id' => $user->id]);
        }

        $approver = $role === 'dean' ? 'the System Admin' : 'the Dean';
        $roleLabel = ['dean' => 'Dean', 'coordinator' => 'OJT Coordinator', 'company' => 'Office/Company'][$role];

        return redirect()->route('login')->with(
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
