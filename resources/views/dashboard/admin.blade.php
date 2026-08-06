@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<style>
    .stat-card-link { text-decoration: none; color: inherit; display: block; }
    .stat-card-link .card { transition: box-shadow 0.15s ease, transform 0.15s ease; }
    .stat-card-link:hover .card { box-shadow: 0 8px 24px rgba(15,23,42,0.10); transform: translateY(-2px); }
</style>
<div class="mb-4">
    <h3 class="mb-1">Admin Dashboard</h3>
    <p class="text-muted mb-0">Here's what's happening across the OJT program.</p>
</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('students.index') }}" class="stat-card-link">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <small class="text-muted">Total Interns</small>
                    <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                </div>
                <h3 class="mb-0">{{ $totalStudents }}</h3>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('students.index') }}" class="stat-card-link">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <small class="text-muted">Deployed</small>
                    <div class="stat-icon"><i class="bi bi-send-check-fill"></i></div>
                </div>
                <h3 class="mb-0">{{ $deployedCount }}</h3>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('students.index') }}" class="stat-card-link">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <small class="text-muted">Completed</small>
                    <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                </div>
                <h3 class="mb-0">{{ $completedCount }}</h3>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('companies.index') }}" class="stat-card-link">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <small class="text-muted">Offices/Companies</small>
                    <div class="stat-icon"><i class="bi bi-building"></i></div>
                </div>
                <h3 class="mb-0">{{ $totalCompanies }}</h3>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route('coordinators.index') }}" class="stat-card-link">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <small class="text-muted">OJT Coordinators</small>
                    <div class="stat-icon"><i class="bi bi-person-badge-fill"></i></div>
                </div>
                <h3 class="mb-0">{{ $totalCoordinators }}</h3>
            </div>
        </a>
    </div>
</div>

<div class="card p-3">
    <h5 class="mb-3">Recent Announcements</h5>
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
