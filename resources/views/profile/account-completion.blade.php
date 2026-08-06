@extends('layouts.app')
@section('title', 'Complete Your Account')
@section('content')
<h3 class="mb-1">Complete Your Account</h3>
<p class="text-muted mb-4">Welcome, {{ explode(' ', $user->name)[0] }}! Just one more step — tell us your specific role. You won't be able to use the rest of the system until this is saved.</p>

<div class="card p-4" style="max-width:700px;">
    <form method="POST" action="{{ route('account-completion.store') }}" autocomplete="off">
        @csrf
        @include('auth.partials.designation-fields')

        <button type="submit" class="btn btn-success mt-2">Submit for Approval</button>
    </form>
</div>

<p class="mt-3">
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link p-0 text-decoration-none">Not ready? Log out and finish this later</button>
    </form>
</p>
@endsection
