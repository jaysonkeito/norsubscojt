@extends('layouts.auth')
@section('title', 'Login')
@section('content')
<div class="card auth-card">
    <div class="card-body p-4 p-md-5">
        <div class="auth-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <h4 class="mb-1 text-center">@include('partials.clock-o')JT Tracker</h4>
        <p class="text-muted text-center mb-4">NORSU Bayawan-Sta. Catalina Campus</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email or Student ID</label>
                <input type="text" name="login" class="form-control" value="{{ old('login') }}" placeholder="Enter your Email or Student ID" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="password-input-group">
                    <input type="password" name="password" id="loginPassword" class="form-control" placeholder="Enter your password" required oninput="toggleEyeVisibility('loginPassword', 'loginEyeBtn')">
                    <button class="btn password-toggle-btn eye-btn-hidden" type="button" id="loginEyeBtn" onclick="togglePassword('loginPassword', 'loginPasswordIcon')">
                        <i class="bi bi-eye" id="loginPasswordIcon"></i>
                    </button>
                </div>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-azure w-100">Login</button>
        </form>
        <p class="text-center mt-3 mb-0">
            No account? <a href="{{ route('register') }}">Register as Student</a>
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
</script>
@endsection