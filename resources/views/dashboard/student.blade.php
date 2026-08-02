@extends('layouts.app')
@section('title', 'My Dashboard')
@section('content')
<h3 class="mb-4">Welcome, {{ auth()->user()->name }}</h3>

@if($student && !$student->isProfileComplete())
<div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2">
    <span>Please update your profile details.</span>
    <a href="{{ route('profile.complete') }}" class="btn btn-sm btn-warning">Update Now</a>
</div>
@endif

@if($student)
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card p-3">
            <small class="text-muted">Required Hours</small>
            <h4>{{ $student->required_hours }}</h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3">
            <small class="text-muted">Rendered Hours</small>
            <h4>{{ $student->renderedHours() }}</h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3">
            <small class="text-muted">Remaining Hours</small>
            <h4>{{ $student->remainingHours() }}</h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3">
            <small class="text-muted">Days Present</small>
            <h4>{{ $student->attendanceSummary()['approved_days'] }}</h4>
        </div>
    </div>
</div>
<div class="card p-3 mb-4">
    <label class="mb-1">Hours Progress: {{ $student->progressPercent() }}%</label>
    <div class="progress"><div class="progress-bar bg-success" style="width: {{ $student->progressPercent() }}%"></div></div>
</div>

@php($attendance = $student->attendanceSummary())
<div class="card p-3 mb-4">
    <h5 class="mb-3">Attendance Summary</h5>
    <div class="row text-center g-3">
        <div class="col-6 col-md-3">
            <div class="text-muted small">Total Days Logged</div>
            <div class="fs-4">{{ $attendance['total_days'] }}</div>
        </div>
        <div class="col-6 col-md-3">
            <div class="text-muted small">Approved</div>
            <div class="fs-4 text-success">{{ $attendance['approved_days'] }}</div>
        </div>
        <div class="col-6 col-md-3">
            <div class="text-muted small">Pending</div>
            <div class="fs-4 text-warning">{{ $attendance['pending_days'] }}</div>
        </div>
        <div class="col-6 col-md-3">
            <div class="text-muted small">Rejected</div>
            <div class="fs-4 text-danger">{{ $attendance['rejected_days'] }}</div>
        </div>
    </div>
</div>

<div class="card p-3">
    <h5>Recent Time Logs (Attendance)</h5>
    <div class="table-responsive">
    <table class="table">
        <thead><tr><th>Date</th><th>Time In</th><th>Time Out</th><th>Hours</th><th>Status</th></tr></thead>
        <tbody>
        @forelse($recentLogs as $log)
            <tr>
                <td>{{ $log->log_date }}</td>
                <td>{{ $log->time_in }}</td>
                <td>{{ $log->time_out }}</td>
                <td>{{ $log->hours_rendered }}</td>
                <td><span class="badge bg-{{ $log->status === 'approved' ? 'success' : ($log->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($log->status) }}</span></td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted">No time logs yet. Your Coordinator or Host Company will record your attendance here.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@else
<div class="alert alert-warning">Your account is not yet linked to a student profile. Please contact the OJT Coordinator.</div>
@endif
@endsection
