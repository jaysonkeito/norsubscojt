@extends('layouts.auth')
@section('title', 'Register')
@section('content')
<div class="card auth-card wide">
    <div class="card-body p-4 p-md-5">
        <div class="auth-brand-icon"><i class="bi bi-person-plus-fill"></i></div>
        <h4 class="mb-1 text-center">Student Registration</h4>
        <p class="text-muted text-center mb-4">@include('partials.clock-o')JT Tracker — NORSU BSC</p>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Student ID No.</label>
                    <input type="text" name="student_id_no" class="form-control" value="{{ old('student_id_no') }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Course</label>
                    <input type="text" name="course" class="form-control" value="{{ old('course') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Year Level</label>
                    <input type="text" name="year_level" class="form-control" value="{{ old('year_level') }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-azure w-100">Register</button>
        </form>
        <p class="text-center mt-3 mb-0">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </p>
    </div>
</div>
@endsection
