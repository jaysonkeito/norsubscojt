<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $profile = $user->companyProfile;
        abort_unless($profile, 403, 'No company profile linked to this account.');

        return view('profile.company-complete', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $profile = $user->companyProfile;
        abort_unless($profile, 403, 'No company profile linked to this account.');

        $validated = $request->validate([
            'mobile_number' => ['required', 'string', 'max:255'],
            'office_landline' => ['nullable', 'string', 'max:255'],
            'id_badge_number' => ['nullable', 'string', 'max:255'],
            'alternate_email' => ['nullable', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $profileData = collect($validated)->except('photo')->toArray();

        if ($request->hasFile('photo')) {
            $profileData['photo_path'] = $request->file('photo')->store('company-photos', 'private');
        }

        $profile->update($profileData);

        return redirect()->route('dashboard')->with('success', 'Your profile is all set!');
    }
}
