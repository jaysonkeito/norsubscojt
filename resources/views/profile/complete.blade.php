@extends('layouts.app')
@section('title', 'Complete Your Profile')
@section('content')
@php
    $nameParts = explode(' ', $user->name, 2);
    $firstName = old('first_name', $nameParts[0] ?? '');
    $lastName = old('last_name', $nameParts[1] ?? '');
@endphp

<h3 class="mb-1">Complete Your Profile</h3>
<p class="text-muted mb-4">Just a few details to finish setting up your Intern account. You won't be able to use the rest of the system until this is saved.</p>

<div class="card p-4">
    <form method="POST" action="{{ route('profile.complete.store') }}" enctype="multipart/form-data">
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

        <h6 class="text-uppercase text-muted small fw-bold mb-3 mt-4" style="letter-spacing:0.05em;">Student Information</h6>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Student ID</label>
                <input type="text" name="student_id_no" class="form-control" value="{{ old('student_id_no', $student->student_id_no) }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Program - Major</label>
                <select name="course" class="form-select" required>
                    <option value="">Select program</option>
                    @foreach($programGroups as $college => $programs)
                        <optgroup label="{{ $college }}">
                            @foreach($programs as $program)
                                <option value="{{ $program }}" {{ old('course', $student->course) === $program ? 'selected' : '' }}>{{ $program }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Year Level</label>
                <select name="year_level" class="form-select" required>
                    <option value="">Select year level</option>
                    @foreach(['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'] as $yr)
                        <option value="{{ $yr }}" {{ old('year_level', $student->year_level) === $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h6 class="text-uppercase text-muted small fw-bold mb-3 mt-4" style="letter-spacing:0.05em;">Personal Details</h6>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">Select gender</option>
                    <option value="Male" {{ old('gender', $student->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $student->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Prefer not to say" {{ old('gender', $student->gender) === 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Birthdate</label>
                <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate', optional($student->birthdate)->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $student->phone_number) }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Guardian Name</label>
                <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name', $student->guardian_name) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Guardian Contact</label>
                <input type="text" name="guardian_contact" class="form-control" value="{{ old('guardian_contact', $student->guardian_contact) }}">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" rows="2" class="form-control">{{ old('address', $student->address) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Profile Photo <span class="text-muted">(optional)</span></label>
            <input type="file" name="photo" accept="image/*" class="form-control">
        </div>

        <h6 class="text-uppercase text-muted small fw-bold mb-3 mt-4" style="letter-spacing:0.05em;">Social Links <span class="text-normal text-muted">(optional)</span></h6>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Facebook</label>
                <input type="url" name="facebook_link" class="form-control" placeholder="https://facebook.com/..." value="{{ old('facebook_link', $student->facebook_link) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">YouTube</label>
                <input type="url" name="youtube_link" class="form-control" placeholder="https://youtube.com/..." value="{{ old('youtube_link', $student->youtube_link) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">LinkedIn</label>
                <input type="url" name="linkedin_link" class="form-control" placeholder="https://linkedin.com/in/..." value="{{ old('linkedin_link', $student->linkedin_link) }}">
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-2">Save &amp; Continue to Dashboard</button>
    </form>
</div>
@endsection
