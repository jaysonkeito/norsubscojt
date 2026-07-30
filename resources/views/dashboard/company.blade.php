@extends('layouts.app')
@section('title', 'Company Dashboard')
@section('content')
<h3 class="mb-1">{{ $company->name ?? 'Host Company' }}</h3>
<p class="text-muted mb-4">Interns currently deployed with your company</p>

<div class="card p-3">
    <div class="table-responsive">
<table class="table">
        <thead><tr><th>Name</th><th>Course</th><th>Status</th><th>Progress</th></tr></thead>
        <tbody>
        @forelse($students as $s)
            <tr>
                <td>{{ $s->user->name }}</td>
                <td>{{ $s->course }}</td>
                <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$s->status)) }}</span></td>
                <td style="width:200px;">
                    <div class="progress"><div class="progress-bar bg-success" style="width: {{ $s->progressPercent() }}%"></div></div>
                    <small>{{ $s->progressPercent() }}%</small>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">No interns assigned to your company yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
</div>
@endsection
