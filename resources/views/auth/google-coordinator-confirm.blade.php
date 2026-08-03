@extends('layouts.auth')
@section('title', 'Confirm Coordinator Account')
@section('content')
<div class="card auth-card">
    <div class="card-body p-4 p-md-5">
        <div class="auth-brand-icon"><i class="bi bi-person-badge-fill"></i></div>
        <h4 class="mb-4 text-center">@include('partials.clock-o')JT Tracker</h4>

        <div class="alert alert-light border mb-3">
            You are about to create an <strong>OJT Coordinator</strong> account with Google for <strong>{{ $email }}</strong>.
        </div>
        <div class="alert alert-warning">
            Coordinator accounts require approval from the System Admin before activation. Are you sure you want to continue?
        </div>

        <form method="POST" action="{{ route('google.coordinator.confirm.store') }}">
            @csrf
            <button type="submit" class="btn btn-azure w-100 mb-2">Yes, continue as Coordinator</button>
        </form>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">No, go back</a>

        <p class="text-muted small text-center mt-3 mb-0">
            If you intended to sign in as a Student, choose "No" and use the Student Google button instead.
        </p>
    </div>
</div>
@endsection
