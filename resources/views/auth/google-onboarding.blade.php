@extends('layouts.auth')
@section('title', 'Complete Your Registration')
@section('content')
<div class="card auth-card wide">
    <div class="card-body p-4 p-md-5">
        <div class="auth-brand-icon"><i class="bi bi-google"></i></div>
        <h4 class="mb-1 text-center">Almost Done, {{ explode(' ', $profile['name'])[0] }}!</h4>
        <p class="text-muted text-center mb-4">Signed in with Google as <strong>{{ $profile['email'] }}</strong>. Just tell us your designation to finish setting up your account.</p>

        <form method="POST" action="{{ route('google.onboarding.store') }}">
            @csrf
            @include('auth.partials.designation-fields')

            <button type="submit" class="btn btn-azure w-100 mt-2">Submit for Approval</button>
        </form>
        <p class="text-center mt-3 mb-0">
            <a href="{{ route('login') }}">Cancel and go back to login</a>
        </p>
    </div>
</div>
@endsection
