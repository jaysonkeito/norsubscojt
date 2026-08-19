@extends('layouts.auth')
@section('title', 'Register')

@section('welcome_side', 'right')
@section('welcome_panel')
    <div class="welcome-inner">
        <div class="auth-brand-icon"><i class="bi bi-person-plus-fill"></i></div>
        <h2>Hello, Friend!</h2>
        <p>Create an account to start logging hours, submitting requirements, and tracking your internship progress.</p>
        <p class="welcome-cta-hint mb-1">Already have an account?</p>
        <a href="{{ route('login') }}" class="btn-welcome-cta"><i class="bi bi-arrow-left"></i> Sign In</a>
    </div>
@endsection

@section('content')
    <div class="form-brand text-center mb-4">
        <div class="auth-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <h4 class="mb-1">OJT Tracker</h4>
        <p class="text-muted mb-0">NORSU Bayawan-Sta. Catalina Campus</p>
    </div>

    <h4 class="mb-1">Create Account</h4>
    <p class="text-muted mb-4">Start your OJT journey — it only takes a minute.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label class="form-label d-block mb-2">I am a</label>
        <div class="btn-group w-100 mb-3 role-toggle" role="group">
            <input type="radio" class="btn-check" name="account_type" id="typeStudent" value="student" autocomplete="off" {{ old('account_type', 'student') === 'student' ? 'checked' : '' }}>
            <label class="btn btn-outline-azure" for="typeStudent">Student</label>

            <input type="radio" class="btn-check" name="account_type" id="typeNonStudent" value="non_student" autocomplete="off" {{ old('account_type') === 'non_student' ? 'checked' : '' }}>
            <label class="btn btn-outline-azure" for="typeNonStudent">Non-Student</label>
        </div>
        <p class="text-muted small mb-4">
            Non-Student? You'll pick your specific role (Dean / OJT Coordinator / Office-Company) on the next screen.
        </p>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Password</label>
                <div class="password-input-group">
                    <input type="password" name="password" id="regPassword" class="form-control" required autocomplete="new-password" oninput="toggleEyeVisibility('regPassword', 'regEyeBtn')">
                    <button class="btn password-toggle-btn eye-btn-hidden" type="button" id="regEyeBtn" onclick="togglePassword('regPassword', 'regPasswordIcon')">
                        <i class="bi bi-eye" id="regPasswordIcon"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Confirm Password</label>
                <div class="password-input-group">
                    <input type="password" name="password_confirmation" id="regPasswordConfirm" class="form-control" required autocomplete="new-password" oninput="toggleEyeVisibility('regPasswordConfirm', 'regConfirmEyeBtn')">
                    <button class="btn password-toggle-btn eye-btn-hidden" type="button" id="regConfirmEyeBtn" onclick="togglePassword('regPasswordConfirm', 'regPasswordConfirmIcon')">
                        <i class="bi bi-eye" id="regPasswordConfirmIcon"></i>
                    </button>
                </div>
            </div>
        </div>

        <hr class="my-4">
        <p class="text-uppercase text-muted small fw-bold text-center mb-3" style="letter-spacing:0.05em;">Personal Details</p>

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

        <div class="form-check mb-4">
            <input type="checkbox" class="form-check-input" id="agreeTerms" required>
            <label class="form-check-label small" for="agreeTerms">I agree to the OJT Program's Data Privacy &amp; Terms of Use.</label>
        </div>

        <button type="submit" class="btn btn-azure w-100">Continue</button>
    </form>

    <p class="text-center mt-3 mb-0">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </p>

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
    </script>
@endsection
