@extends('layouts.app')
@section('title', 'Time Logs (DTR)')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Time Logs (DTR)</h3>
    @if(auth()->user()->isStudent())
        <a href="{{ route('timelogs.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Log Time</a>
    @endif
</div>
<div class="card p-3">
    <div class="table-responsive">
<table class="table table-hover">
        <thead><tr>
            @unless(auth()->user()->isStudent())<th>Student</th>@endunless
            <th>Date</th><th>Time In</th><th>Time Out</th><th>Hours</th><th>Status</th><th>Actions</th>
        </tr></thead>
        <tbody>
        @forelse($logs as $log)
            <tr>
                @unless(auth()->user()->isStudent())<td>{{ $log->student->user->name ?? '—' }}</td>@endunless
                <td>{{ $log->log_date }}</td>
                <td>{{ $log->time_in }}</td>
                <td>{{ $log->time_out }}</td>
                <td>{{ $log->hours_rendered }}</td>
                <td><span class="badge bg-{{ $log->status === 'approved' ? 'success' : ($log->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($log->status) }}</span></td>
                <td>
                    @if(in_array(auth()->user()->role, ['admin','coordinator','company']) && $log->status === 'pending')
                        <form action="{{ route('timelogs.status', $log) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button class="btn btn-sm btn-outline-success">Approve</button>
                        </form>
                        <form action="{{ route('timelogs.status', $log) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button class="btn btn-sm btn-outline-danger">Reject</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted">No time logs yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
    {{ $logs->links() ?? '' }}
</div>
@endsection
