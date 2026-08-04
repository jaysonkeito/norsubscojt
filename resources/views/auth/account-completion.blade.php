@extends('layouts.auth')
@section('title', 'Complete Your Account')
@section('content')
<div class="card auth-card wide">
    <div class="card-body p-4 p-md-5">
        <div class="auth-brand-icon"><i class="bi bi-person-check-fill"></i></div>
        <h4 class="mb-1 text-center">Complete Your Account</h4>
        <p class="text-muted text-center mb-4">Welcome, {{ explode(' ', $basics['name'])[0] }}! Just one more step — tell us your specific role.</p>

        <form method="POST" action="{{ route('account-completion.store') }}">
            @csrf
            @include('auth.partials.designation-fields')

            <button type="submit" class="btn btn-azure w-100 mt-2">Submit for Approval</button>
        </form>
        <p class="text-center mt-3 mb-0">
            <a href="{{ route('register') }}">Start over</a>
        </p>
    </div>
</div>
@endsection
