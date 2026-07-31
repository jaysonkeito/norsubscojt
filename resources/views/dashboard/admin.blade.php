@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<style>
    .stat-card-link { text-decoration: none; color: inherit; display: block; }
    .stat-card-link .card { transition: box-shadow 0.15s ease, transform 0.15s ease; }
    .stat-card-link:hover .card { box-shadow: 0 4px 14px rgba(10,90,168,0.25); transform: translateY(-2px); }
</style>
<h3 class="mb-4">Admin Dashboard</h3>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="{{ route('students.index') }}" class="stat-card-link">
            <div class="card p-3">
                <small class="text-muted">Total Interns</small>
                <h3>{{ $totalStudents }}</h3>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('students.index') }}" class="stat-card-link">
            <div class="card p-3">
                <small class="text-muted">Deployed</small>
                <h3>{{ $deployedCount }}</h3>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('students.index') }}" class="stat-card-link">
            <div class="card p-3">
                <small class="text-muted">Completed</small>
                <h3>{{ $completedCount }}</h3>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('companies.index') }}" class="stat-card-link">
            <div class="card p-3">
                <small class="text-muted">Partner Offices/Companies</small>
                <h3>{{ $totalCompanies }}</h3>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('coordinators.index') }}" class="stat-card-link">
            <div class="card p-3">
                <small class="text-muted">OJT Coordinators</small>
                <h3>{{ $totalCoordinators }}</h3>
            </div>
        </a>
    </div>
</div>

<div class="card p-3">
    <h5>Recent Announcements</h5>
    @forelse($announcements as $a)
        <div class="border-bottom py-2">
            <strong>{{ $a->title }}</strong>
            <p class="mb-0 text-muted small">{{ Str::limit($a->content, 120) }}</p>
        </div>
    @empty
        <p class="text-muted mb-0">No announcements yet.</p>
    @endforelse
</div>
@endsection