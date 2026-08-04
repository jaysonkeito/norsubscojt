@extends('layouts.app')
@section('title', 'Pending Approvals')
@section('content')
<h3 class="mb-4">Pending Approvals</h3>
<p class="text-muted">Dean, OJT Coordinator, and Office/Company self-registrations wait here until approved or rejected. Student accounts don't require approval and can log in immediately.</p>
@if(auth()->user()->isDean())
<p class="text-muted small">As Dean, you can approve/reject OJT Coordinator and Office/Company requests. Dean account requests are approved by the System Admin.</p>
@endif

<div class="card p-3">
    <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead><tr><th>Name</th><th>Email</th><th>Requested Role</th><th>Company Name</th><th>Registered</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($pendingUsers as $u)
            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td><span class="badge bg-secondary">{{ ['dean' => 'Dean', 'coordinator' => 'OJT Coordinator', 'company' => 'Office/Company'][$u->role] ?? ucfirst($u->role) }}</span></td>
                <td>{{ $u->company->name ?? '—' }}</td>
                <td>{{ $u->created_at->format('M d, Y g:i A') }}</td>
                <td>
                    <form action="{{ route('pending-approvals.approve', $u) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-success">Approve</button>
                    </form>
                    <form action="{{ route('pending-approvals.reject', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject and permanently remove this registration?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Reject</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted">No pending registrations right now.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
