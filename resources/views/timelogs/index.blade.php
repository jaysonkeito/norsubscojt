@extends('layouts.app')
@section('title', 'Time Logs (Attendance)')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h3 class="mb-0">Time Logs (Attendance)</h3>
    @if(in_array(auth()->user()->role, ['admin', 'coordinator', 'company']))
        <a href="{{ route('timelogs.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Record Time</a>
    @endif
</div>

@if(auth()->user()->isStudent())
    <p class="text-muted small mb-3">Your Coordinator or Host Company records your time in/out. This page is view-only — use the filters below to see how many hours you've completed.</p>
@endif

<div class="card p-3 mb-3">
    <form method="GET" action="{{ route('timelogs.index') }}" class="row g-2 align-items-end">
        <div class="col-auto">
            <label class="form-label small mb-1">Period</label>
            <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="day" {{ $period === 'day' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ $period === 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option>
                <option value="range" {{ $period === 'range' ? 'selected' : '' }}>Custom Range</option>
            </select>
        </div>
        @if($period === 'range')
        <div class="col-auto">
            <label class="form-label small mb-1">From</label>
            <input type="date" name="start" class="form-control form-control-sm" value="{{ request('start', $rangeStart->format('Y-m-d')) }}">
        </div>
        <div class="col-auto">
            <label class="form-label small mb-1">To</label>
            <input type="date" name="end" class="form-control form-control-sm" value="{{ request('end', $rangeEnd->format('Y-m-d')) }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-outline-primary">Apply</button>
        </div>
        @endif
        <div class="col-auto ms-auto text-end">
            <small class="text-muted d-block">{{ $rangeStart->format('M d, Y') }} – {{ $rangeEnd->format('M d, Y') }}</small>
            <span class="fs-5 fw-bold text-success">{{ number_format($totalHours, 2) }} hrs</span>
            <small class="text-muted d-block">approved this period</small>
        </div>
    </form>
</div>

<div class="card p-3">
    <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
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
                    @if(in_array(auth()->user()->role, ['admin','coordinator','company']))
                        @if($log->status === 'pending')
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
                        <form action="{{ route('timelogs.destroy', $log) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this time log?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-secondary">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted">No time logs for this period.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
    {{ $logs->links() }}
</div>
@endsection
