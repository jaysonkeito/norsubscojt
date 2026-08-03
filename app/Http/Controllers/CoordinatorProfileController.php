<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoordinatorProfileController extends Controller
{
    /**
     * Departments, reusing the same NORSU college list used for the
     * Intern's Program-Major dropdown.
     */
    public static function departments(): array
    {
        return array_keys(StudentProfileController::programGroups());
    }

    public static function designations(): array
    {
        return [
            'OJT Coordinator',
            'Program Coordinator',
            'Department Chairperson',
            'Dean',
            'Vice President for Academic Affairs',
            'Campus Director',
        ];
    }

    public function show(Request $request)
    {
        $user = $request->user();
        $profile = $user->coordinatorProfile;
        abort_unless($profile, 403, 'No coordinator profile linked to this account.');

        return view('profile.coordinator-complete', [
            'user' => $user,
            'profile' => $profile,
            'departments' => self::departments(),
            'designations' => self::designations(),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $profile = $user->coordinatorProfile;
        abort_unless($profile, 403, 'No coordinator profile linked to this account.');

        $validated = $request->validate([
            // Account details
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // Coordinator / instructor information
            'employee_id' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string'],
            'designation' => ['required', 'string'],
            'prefix_title' => ['nullable', 'string', 'max:255'],
            'suffix_title' => ['nullable', 'string', 'max:255'],
            'institutional_email' => ['nullable', 'email', 'max:255'],
            'gender' => ['nullable', 'string'],
            'civil_status' => ['nullable', 'string'],
            'mobile_number' => ['nullable', 'string'],
            'date_hired' => ['nullable', 'date'],
            'qualification' => ['nullable', 'string'],
            'specialization' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'name' => trim($validated['first_name'] . ' ' . $validated['last_name']),
        ]);

        $profileData = collect($validated)->except([
            'username', 'email', 'first_name', 'last_name', 'photo', 'resume',
        ])->toArray();

        if ($request->hasFile('photo')) {
            $profileData['photo_path'] = $request->file('photo')->store('coordinator-photos', 'public');
        }
        if ($request->hasFile('resume')) {
            $profileData['resume_path'] = $request->file('resume')->store('coordinator-resumes', 'public');
        }

        $profile->update($profileData);

        return redirect()->route('dashboard')->with('success', 'Your profile is all set!');
    }
}
