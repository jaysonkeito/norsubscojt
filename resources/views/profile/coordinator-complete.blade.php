@extends('layouts.app')
@section('title', 'Complete Your Profile')
@section('content')
@php
    $nameParts = explode(' ', $user->name, 2);
    $firstName = old('first_name', $nameParts[0] ?? '');
    $lastName = old('last_name', $nameParts[1] ?? '');
@endphp

<h3 class="mb-1">Complete Your Profile</h3>
<p class="text-muted mb-4">Just a few details to finish setting up your OJT Coordinator account. You won't be able to use the rest of the system until this is saved.</p>

<div class="card p-4">
    <form method="POST" action="{{ route('coordinator-profile.complete.store') }}" enctype="multipart/form-data">
        @csrf

        <h6 class="text-uppercase text-muted small fw-bold mb-3" style="letter-spacing:0.05em;">Account Details</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" value="{{ $firstName }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="{{ $lastName }}" required>
            </div>
        </div>

        <h6 class="text-uppercase text-muted small fw-bold mb-3 mt-4" style="letter-spacing:0.05em;">Coordinator Information</h6>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Employee ID</label>
                <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id', $profile->employee_id) }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Prefix Title <span class="text-muted">(optional)</span></label>
                <input type="text" name="prefix_title" class="form-control" placeholder="e.g. Dr., Engr." value="{{ old('prefix_title', $profile->prefix_title) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Suffix Title <span class="text-muted">(optional)</span></label>
                <input type="text" name="suffix_title" class="form-control" placeholder="e.g. Jr., PhD" value="{{ old('suffix_title', $profile->suffix_title) }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Institutional Email <span class="text-muted">(optional)</span></label>
                <input type="email" name="institutional_email" class="form-control" value="{{ old('institutional_email', $profile->institutional_email) }}">
                <small class="text-muted">Used for official campus communications.</small>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">Select gender</option>
                    <option value="Male" {{ old('gender', $profile->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $profile->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Prefer not to say" {{ old('gender', $profile->gender) === 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Civil Status</label>
                <select name="civil_status" class="form-select">
                    <option value="">Select status</option>
                    @foreach(['Single', 'Married', 'Widowed', 'Separated'] as $cs)
                        <option value="{{ $cs }}" {{ old('civil_status', $profile->civil_status) === $cs ? 'selected' : '' }}>{{ $cs }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Department / College</label>
                <select name="department" class="form-select" required>
                    <option value="">Select department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ old('department', $profile->department) === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Designation</label>
                <select name="designation" class="form-select" required>
                    <option value="">Select designation</option>
                    @foreach($designations as $d)
                        <option value="{{ $d }}" {{ old('designation', $profile->designation) === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Mobile Number</label>
                <input type="text" name="mobile_number" class="form-control" value="{{ old('mobile_number', $profile->mobile_number) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Date Hired</label>
                <input type="date" name="date_hired" class="form-control" value="{{ old('date_hired', optional($profile->date_hired)->format('Y-m-d')) }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Photo <span class="text-muted">(optional)</span></label>
                <input type="file" name="photo" accept="image/*" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Resume / CV <span class="text-muted">(optional)</span></label>
                <input type="file" name="resume" accept=".pdf,.doc,.docx" class="form-control">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Qualification <span class="text-muted">(optional)</span></label>
            <textarea name="qualification" rows="2" class="form-control" placeholder="e.g. MA in Education, Licensed Professional Teacher">{{ old('qualification', $profile->qualification) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Specialization <span class="text-muted">(optional)</span></label>
            <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $profile->specialization) }}">
        </div>

        <button type="submit" class="btn btn-success mt-2">Save &amp; Continue to Dashboard</button>
    </form>
</div>
@endsection
