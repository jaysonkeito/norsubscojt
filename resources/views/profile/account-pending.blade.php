@extends('layouts.app')
@section('title', 'Pending Approval')
@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: 60vh;">
    <div class="card p-4 p-md-5 text-center" style="max-width:520px;">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:64px;height:64px;border-radius:16px;background:#EFF6FF;">
            <i class="bi bi-hourglass-split" style="font-size:1.75rem;color:#3B82F6;"></i>
        </div>
        <h4 class="mb-2">Pending Approval</h4>
        <p class="text-muted mb-3">
            Hi {{ explode(' ', $user->name)[0] }}, your
            <strong>{{ ['dean' => 'Dean', 'coordinator' => 'OJT Coordinator', 'company' => 'Office/Company'][$user->role] ?? ucfirst($user->role) }}</strong>
            account is waiting for approval from
            {{ $user->role === 'dean' ? 'the System Admin' : 'the Dean' }}.
        </p>
        <p class="text-muted small mb-4">You'll be able to log in and use the system as soon as it's approved — no need to register again, just check back and log in later.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">Logout</button>
        </form>
    </div>
</div>
@endsection
