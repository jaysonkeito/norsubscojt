@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
@php
    $nameParts = explode(' ', $user->name, 2);
    $firstName = old('first_name', $nameParts[0] ?? '');
    $lastName = old('last_name', $nameParts[1] ?? '');
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">My Profile</h3>
    <span class="badge bg-secondary text-capitalize">{{ $user->role }}</span>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card p-4">
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Photo preview --}}
        @php
            $photoUrl = null;
            if ($user->isStudent() && $user->student?->photo_path) {
                $photoUrl = route('files.student-photo', $user->student);
            } elseif ($user->isCoordinator() && $user->coordinatorProfile?->photo_path) {
                $photoUrl = route('files.coordinator-photo', $user->coordinatorProfile);
            } elseif ($user->isCompany() && $user->companyProfile?->photo_path) {
                $photoUrl = route('files.company-photo', $user->companyProfile);
            } elseif ($user->photo_path) {
                $photoUrl = route('files.user-photo', $user);
            }
        @endphp

        @if($photoUrl)
            <div class="mb-3">
                <label class="form-label">Current Photo</label>
                <div>
                    <img src="{{ $photoUrl }}" alt="Profile Photo" class="rounded-circle" style="width:80px;height:80px;object-fit:cover;">
                </div>
            </div>
        @endif

        <h6 class="text-uppercase text-muted small fw-bold mb-3" style="letter-spacing:0.05em;">Account Details</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
        </div>

        {{-- Student fields --}}
        @if($user->isStudent() && $user->student)
            @php $student = $user->student; @endphp
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
                        @foreach(\App\Http\Controllers\StudentProfileController::programGroups() as $college => $programs)
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
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $student->contact_number) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $student->address) }}">
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

            <div class="mb-3">
                <label class="form-label">Profile Photo <span class="text-muted">(optional)</span></label>
                <input type="file" name="photo" accept="image/*" class="form-control">
            </div>
        @endif

        {{-- Coordinator fields --}}
        @if($user->isCoordinator() && $user->coordinatorProfile)
            @php $profile = $user->coordinatorProfile; @endphp
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
                        @foreach(['College of Agriculture and Forestry', 'College of Arts and Sciences', 'College of Business Administration', 'College of Criminal Justice Education', 'College of Industrial Technology', 'College of Teacher Education'] as $dept)
                            <option value="{{ $dept }}" {{ old('department', $profile->department) === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Designation</label>
                    <select name="designation" class="form-select" required>
                        <option value="">Select designation</option>
                        @foreach(['OJT Coordinator', 'Department Head', 'Faculty', 'Staff'] as $d)
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
            <div class="mb-3">
                <label class="form-label">Qualification <span class="text-muted">(optional)</span></label>
                <textarea name="qualification" rows="2" class="form-control" placeholder="e.g. MA in Education, Licensed Professional Teacher">{{ old('qualification', $profile->qualification) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Specialization <span class="text-muted">(optional)</span></label>
                <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $profile->specialization) }}">
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
        @endif

        {{-- Company fields --}}
        @if($user->isCompany() && $user->companyProfile)
            @php $profile = $user->companyProfile; @endphp
            <h6 class="text-uppercase text-muted small fw-bold mb-3 mt-4" style="letter-spacing:0.05em;">Company Representative Information</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile_number" class="form-control" value="{{ old('mobile_number', $profile->mobile_number) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Office Landline <span class="text-muted">(optional)</span></label>
                    <input type="text" name="office_landline" class="form-control" value="{{ old('office_landline', $profile->office_landline) }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">ID / Badge Number <span class="text-muted">(optional)</span></label>
                    <input type="text" name="id_badge_number" class="form-control" value="{{ old('id_badge_number', $profile->id_badge_number) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Alternate Email <span class="text-muted">(optional)</span></label>
                    <input type="email" name="alternate_email" class="form-control" value="{{ old('alternate_email', $profile->alternate_email) }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Photo <span class="text-muted">(optional)</span></label>
                <input type="file" name="photo" accept="image/*" class="form-control">
            </div>
        @endif

        {{-- Admin / Dean — account-level photo (no dedicated profile table) --}}
        @if(! ($user->isStudent() && $user->student) && ! ($user->isCoordinator() && $user->coordinatorProfile) && ! ($user->isCompany() && $user->companyProfile))
            <h6 class="text-uppercase text-muted small fw-bold mb-3 mt-4" style="letter-spacing:0.05em;">Profile Photo</h6>
            <div class="mb-3">
                <label class="form-label">Photo <span class="text-muted">(optional)</span></label>
                <input type="file" name="photo" accept="image/*" class="form-control">
            </div>
        @endif

        <button type="submit" class="btn btn-primary mt-2">Save Changes</button>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mt-2 ms-2">Cancel</a>
    </form>
</div>
@endsection
