@extends('layouts.auth')
@section('title', 'Reset Password')
@section('content')
<div class="card auth-card">
    <div class="card-body p-4 p-md-5">
        <div class="auth-brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
        <h4 class="mb-1 text-center">Reset Your Password</h4>
        <p class="text-muted text-center mb-4">Choose a new password below.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $email) }}" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <div class="password-input-group">
                    <input type="password" name="password" id="resetPassword" class="form-control" required oninput="toggleEyeVisibility('resetPassword', 'resetEyeBtn')">
                    <button class="btn password-toggle-btn eye-btn-hidden" type="button" id="resetEyeBtn" onclick="togglePassword('resetPassword', 'resetPasswordIcon')">
                        <i class="bi bi-eye" id="resetPasswordIcon"></i>
                    </button>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <div class="password-input-group">
                    <input type="password" name="password_confirmation" id="resetPasswordConfirm" class="form-control" required oninput="toggleEyeVisibility('resetPasswordConfirm', 'resetConfirmEyeBtn')">
                    <button class="btn password-toggle-btn eye-btn-hidden" type="button" id="resetConfirmEyeBtn" onclick="togglePassword('resetPasswordConfirm', 'resetPasswordConfirmIcon')">
                        <i class="bi bi-eye" id="resetPasswordConfirmIcon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-azure w-100">Reset Password</button>
        </form>
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
</script>
@endsection
