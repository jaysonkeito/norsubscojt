<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::withCount('students')->latest()->paginate(15);
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'affiliation_type' => ['nullable', 'in:inside_campus,outside_campus'],
            'address' => ['nullable', 'string'],
            'contact_person' => ['nullable', 'string'],
            'contact_number' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'industry' => ['nullable', 'string'],
            'moa_status' => ['required', 'in:none,pending,active,expired'],
            'login_email' => ['nullable', 'email', 'unique:users,email'],
            'login_password' => ['nullable', 'string', 'min:6'],
        ]);

        $company = Company::create([
            'name' => $validated['name'],
            'affiliation_type' => $validated['affiliation_type'] ?? null,
            'address' => $validated['address'] ?? null,
            'contact_person' => $validated['contact_person'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'email' => $validated['email'] ?? null,
            'industry' => $validated['industry'] ?? null,
            'moa_status' => $validated['moa_status'],
        ]);

        $message = 'Company added successfully.';

        // Optionally create a login account for the Host Company representative
        if (!empty($validated['login_email']) && !empty($validated['login_password'])) {
            $user = User::create([
                'name' => $validated['contact_person'] ?: $validated['name'],
                'email' => $validated['login_email'],
                'password' => Hash::make($validated['login_password']),
                'role' => 'company',
                'company_id' => $company->id,
            ]);
            CompanyProfile::create(['user_id' => $user->id]);
            $message .= ' A login account was also created for this Host Company.';
        }

        return redirect()->route('companies.index')->with('success', $message);
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'affiliation_type' => ['nullable', 'in:inside_campus,outside_campus'],
            'address' => ['nullable', 'string'],
            'contact_person' => ['nullable', 'string'],
            'contact_number' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'industry' => ['nullable', 'string'],
            'moa_status' => ['required', 'in:none,pending,active,expired'],
        ]);

        $company->update($validated);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company removed.');
    }
}
