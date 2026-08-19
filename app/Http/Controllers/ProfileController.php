<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load([
            'student',
            'coordinatorProfile',
            'companyProfile',
        ]);

        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $user->load(['student', 'coordinatorProfile', 'companyProfile']);

        if ($user->isStudent() && $user->student) {
            return $this->updateStudent($request, $user);
        }

        if ($user->isCoordinator() && $user->coordinatorProfile) {
            return $this->updateCoordinator($request, $user);
        }

        if ($user->isCompany() && $user->companyProfile) {
            return $this->updateCompany($request, $user);
        }

        // Admin / Dean — account-level fields + user-level photo
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $userData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->hasFile('photo')) {
            if ($user->photo_path) {
                Storage::disk('private')->delete($user->photo_path);
            }
            $userData['photo_path'] = $request->file('photo')->store('user-photos', 'private');
        }

        $user->update($userData);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    private function updateStudent(Request $request, $user)
    {
        $student = $user->student;

        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'student_id_no'     => ['required', 'string', 'unique:students,student_id_no,' . $student->id],
            'course'            => ['required', 'string'],
            'year_level'        => ['required', 'string'],
            'contact_number'    => ['nullable', 'string'],
            'gender'            => ['nullable', 'string'],
            'birthdate'         => ['nullable', 'date'],
            'phone_number'      => ['nullable', 'string'],
            'guardian_name'     => ['nullable', 'string'],
            'guardian_contact'  => ['nullable', 'string'],
            'address'           => ['nullable', 'string'],
            'facebook_link'     => ['nullable', 'url'],
            'youtube_link'      => ['nullable', 'url'],
            'linkedin_link'     => ['nullable', 'url'],
            'photo'             => ['nullable', 'image', 'max:2048'],
        ]);

        // Update User fields
        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update Student fields
        $studentData = collect($validated)->except(['name', 'email', 'photo'])->toArray();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($student->photo_path) {
                Storage::disk('private')->delete($student->photo_path);
            }
            $studentData['photo_path'] = $request->file('photo')->store('student-photos', 'private');
        }

        $student->update($studentData);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    private function updateCoordinator(Request $request, $user)
    {
        $profile = $user->coordinatorProfile;

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'employee_id'           => ['required', 'string'],
            'prefix_title'          => ['nullable', 'string'],
            'suffix_title'          => ['nullable', 'string'],
            'institutional_email'   => ['nullable', 'email'],
            'gender'                => ['nullable', 'string'],
            'civil_status'          => ['nullable', 'string'],
            'department'            => ['required', 'string'],
            'designation'           => ['required', 'string'],
            'mobile_number'         => ['nullable', 'string'],
            'date_hired'            => ['nullable', 'date'],
            'qualification'         => ['nullable', 'string'],
            'specialization'        => ['nullable', 'string'],
            'photo'                 => ['nullable', 'image', 'max:2048'],
            'resume'                => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:4096'],
        ]);

        // Update User fields
        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update CoordinatorProfile fields
        $profileData = collect($validated)->except(['name', 'email', 'photo', 'resume'])->toArray();

        if ($request->hasFile('photo')) {
            if ($profile->photo_path) {
                Storage::disk('private')->delete($profile->photo_path);
            }
            $profileData['photo_path'] = $request->file('photo')->store('coordinator-photos', 'private');
        }

        if ($request->hasFile('resume')) {
            if ($profile->resume_path) {
                Storage::disk('private')->delete($profile->resume_path);
            }
            $profileData['resume_path'] = $request->file('resume')->store('coordinator-resumes', 'private');
        }

        $profile->update($profileData);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    private function updateCompany(Request $request, $user)
    {
        $profile = $user->companyProfile;

        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'mobile_number'     => ['required', 'string'],
            'office_landline'   => ['nullable', 'string'],
            'id_badge_number'   => ['nullable', 'string'],
            'alternate_email'   => ['nullable', 'email'],
            'photo'             => ['nullable', 'image', 'max:2048'],
        ]);

        // Update User fields
        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update CompanyProfile fields
        $profileData = collect($validated)->except(['name', 'email', 'photo'])->toArray();

        if ($request->hasFile('photo')) {
            if ($profile->photo_path) {
                Storage::disk('private')->delete($profile->photo_path);
            }
            $profileData['photo_path'] = $request->file('photo')->store('company-photos', 'private');
        }

        $profile->update($profileData);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
}
