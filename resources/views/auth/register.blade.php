@extends('layouts.auth')
@section('title', 'Register')
@section('content')
<div class="card auth-card wide">
    <div class="card-body p-4 p-md-5">
        <div class="auth-brand-icon"><i class="bi bi-person-plus-fill"></i></div>
        <h4 class="mb-1 text-center">Create Your Account</h4>
        <p class="text-muted text-center mb-4">@include('partials.clock-o')JT Tracker — NORSU BSC</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">I am registering as</label>
                <select name="account_type" id="accountType" class="form-select" required onchange="toggleRoleFields()">
                    <option value="student" {{ old('account_type', 'student') === 'student' ? 'selected' : '' }}>Intern (Student)</option>
                    <option value="coordinator" {{ old('account_type') === 'coordinator' ? 'selected' : '' }}>OJT Coordinator</option>
                    <option value="company" {{ old('account_type') === 'company' ? 'selected' : '' }}>Office/Company</option>
                </select>
                <small class="text-muted" id="roleHint">Intern accounts are activated immediately — no approval needed.</small>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            {{-- Student-only fields --}}
            <div id="studentFields" class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Student ID No.</label>
                    <input type="text" name="student_id_no" class="form-control" value="{{ old('student_id_no') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Course</label>
                    <input type="text" name="course" class="form-control" value="{{ old('course') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Year Level</label>
                    <input type="text" name="year_level" class="form-control" value="{{ old('year_level') }}">
                </div>
            </div>

            {{-- Office/Company-only field --}}
            <div id="companyFields" class="row d-none">
                <div class="col-12 mb-3">
                    <label class="form-label">Company / Office Name</label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <div class="password-input-group">
                        <input type="password" name="password" id="regPassword" class="form-control" required oninput="toggleEyeVisibility('regPassword', 'regEyeBtn')">
                        <button class="btn password-toggle-btn eye-btn-hidden" type="button" id="regEyeBtn" onclick="togglePassword('regPassword', 'regPasswordIcon')">
                            <i class="bi bi-eye" id="regPasswordIcon"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password</label>
                    <div class="password-input-group">
                        <input type="password" name="password_confirmation" id="regPasswordConfirm" class="form-control" required oninput="toggleEyeVisibility('regPasswordConfirm', 'regConfirmEyeBtn')">
                        <button class="btn password-toggle-btn eye-btn-hidden" type="button" id="regConfirmEyeBtn" onclick="togglePassword('regPasswordConfirm', 'regPasswordConfirmIcon')">
                            <i class="bi bi-eye" id="regPasswordConfirmIcon"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-azure w-100">Create Account</button>
        </form>
        <p class="text-center mt-3 mb-0">
            Already have an account? <a href="{{ route('login') }}">Sign in</a>
        </p>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

function toggleEyeVisibility(inputId, buttonId) {
    const input = document.getElementById(inputId);
    const button = document.getElementById(buttonId);
    if (input.value.length > 0) {
        button.classList.remove('eye-btn-hidden');
    } else {
        button.classList.add('eye-btn-hidden');
    }
}

function toggleRoleFields() {
    const type = document.getElementById('accountType').value;
    const studentFields = document.getElementById('studentFields');
    const companyFields = document.getElementById('companyFields');
    const studentInputs = studentFields.querySelectorAll('input');
    const companyInputs = companyFields.querySelectorAll('input');
    const hint = document.getElementById('roleHint');

    if (type === 'student') {
        studentFields.classList.remove('d-none');
        companyFields.classList.add('d-none');
        studentInputs.forEach(i => i.required = true);
        companyInputs.forEach(i => i.required = false);
        hint.textContent = 'Intern accounts are activated immediately — no approval needed.';
    } else if (type === 'coordinator') {
        studentFields.classList.add('d-none');
        companyFields.classList.add('d-none');
        studentInputs.forEach(i => i.required = false);
        companyInputs.forEach(i => i.required = false);
        hint.textContent = 'OJT Coordinator accounts require approval from the System Admin before you can log in.';
    } else {
        studentFields.classList.add('d-none');
        companyFields.classList.remove('d-none');
        studentInputs.forEach(i => i.required = false);
        companyInputs.forEach(i => i.required = true);
        hint.textContent = 'Office/Company accounts require approval from the System Admin before you can log in.';
    }
}

document.addEventListener('DOMContentLoaded', toggleRoleFields);
</script>
@endsection
