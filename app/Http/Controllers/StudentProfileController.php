<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    /**
     * NORSU Bayawan-Sta. Catalina Campus college/program list, grouped for
     * the "Program - Major" dropdown on the profile-completion form.
     */
    public static function programGroups(): array
    {
        return [
            'College of Agriculture and Forestry' => [
                'Bachelor of Science in Agronomy',
                'Bachelor of Science in Animal Science',
                'Bachelor of Science in Forestry',
            ],
            'College of Arts and Sciences' => [
                'Bachelor of Science in Computer Science',
                'Bachelor of Science in Information Technology',
            ],
            'College of Business Administration' => [
                'Bachelor of Science in Business Administration Major in Human Resource Management',
                'Bachelor of Science in Hospitality Management',
                'Bachelor of Science in Office Administration',
            ],
            'College of Criminal Justice Education' => [
                'Bachelor of Science in Criminology',
            ],
            'College of Industrial Technology' => [
                'Bachelor of Science in Industrial Technology Major in Automotive',
                'Bachelor of Science in Industrial Technology Major in Computer Technology',
                'Bachelor of Science in Industrial Technology Major in Electrical',
            ],
            'College of Teacher Education' => [
                'Bachelor of Elementary Education Major in General Curriculum',
                'Bachelor of Secondary Education Major in English',
                'Bachelor of Secondary Education Major in Mathematics',
                'Bachelor of Secondary Education Major in Science',
            ],
        ];
    }

    public function show(Request $request)
    {
        $user = $request->user();
        $student = $user->student;
        abort_unless($student, 403, 'No student profile linked to this account.');

        return view('profile.complete', [
            'user' => $user,
            'student' => $student,
            'programGroups' => self::programGroups(),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $student = $user->student;
        abort_unless($student, 403, 'No student profile linked to this account.');

        $validated = $request->validate([
            // Account details
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // Student information
            'student_id_no' => ['required', 'string', 'unique:students,student_id_no,' . $student->id],
            'course' => ['required', 'string'],
            'year_level' => ['required', 'string'],
            'contact_number' => ['nullable', 'string'],
            'gender' => ['nullable', 'string'],
            'birthdate' => ['nullable', 'date'],
            'phone_number' => ['nullable', 'string'],
            'guardian_name' => ['nullable', 'string'],
            'guardian_contact' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'facebook_link' => ['nullable', 'url'],
            'youtube_link' => ['nullable', 'url'],
            'linkedin_link' => ['nullable', 'url'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        // Update account-level fields on the User record
        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'name' => trim($validated['first_name'] . ' ' . $validated['last_name']),
        ]);

        $studentData = collect($validated)->except([
            'username', 'email', 'first_name', 'last_name', 'photo',
        ])->toArray();

        if ($request->hasFile('photo')) {
            $studentData['photo_path'] = $request->file('photo')->store('student-photos', 'private');
        }

        $student->update($studentData);

        return redirect()->route('dashboard')->with('success', 'Your profile is all set!');
    }
}
