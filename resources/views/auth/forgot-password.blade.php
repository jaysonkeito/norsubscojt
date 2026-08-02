@extends('layouts.auth')
@section('title', 'Forgot Password')
@section('content')
<div class="card auth-card">
    <div class="card-body p-4 p-md-5">
        <div class="auth-brand-icon"><i class="bi bi-key-fill"></i></div>
        <h4 class="mb-1 text-center">Forgot Your Password?</h4>
        <p class="text-muted text-center mb-4">Enter your email and we'll send you a reset link.</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
            </div>
            <button type="submit" class="btn btn-azure w-100">Send Reset Link</button>
        </form>
        <p class="text-center mt-3 mb-0">
            <a href="{{ route('login') }}">Back to Login</a>
        </p>
    </div>
</div>
@endsection
