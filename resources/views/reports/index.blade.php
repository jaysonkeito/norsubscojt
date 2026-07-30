@extends('layouts.app')
@section('title', 'Reports')
@section('content')
<h3 class="mb-4">OJT Hours Summary Report</h3>
<div class="card p-3">
    <div class="table-responsive">
<table class="table table-hover">
        <thead><tr><th>Student</th><th>ID No.</th><th>Company</th><th>Required</th><th>Rendered</th><th>Remaining</th><th>Progress</th><th>Status</th></tr></thead>
        <tbody>
        @forelse($students as $s)
            <tr>
                <td>{{ $s['name'] }}</td>
                <td>{{ $s['student_id_no'] }}</td>
                <td>{{ $s['company'] }}</td>
                <td>{{ $s['required_hours'] }}</td>
                <td>{{ $s['rendered_hours'] }}</td>
                <td>{{ $s['remaining_hours'] }}</td>
                <td style="width:150px;">
                    <div class="progress"><div class="progress-bar bg-success" style="width: {{ $s['progress'] }}%"></div></div>
                    <small>{{ $s['progress'] }}%</small>
                </td>
                <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$s['status'])) }}</span></td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-muted">No data available.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
</div>
@endsection
