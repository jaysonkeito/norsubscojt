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
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
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
@endsection
