@extends('layouts.app')
@section('title', 'Students')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Interns</h3>
    <a href="{{ route('students.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Add Intern</a>
</div>
<div class="card p-3">
    <div class="table-responsive">
<table class="table table-hover">
        <thead><tr><th>ID No.</th><th>Name</th><th>Course</th><th>Company</th><th>Coordinator</th><th>Status</th><th>Progress</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($students as $s)
            <tr>
                <td>{{ $s->student_id_no }}</td>
                <td>{{ $s->user->name }}</td>
                <td>{{ $s->course }} - {{ $s->year_level }}</td>
                <td>{{ $s->company->name ?? '—' }}</td>
                <td>{{ $s->coordinator->name ?? '—' }}</td>
                <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$s->status)) }}</span></td>
                <td style="width:150px;">
                    <div class="progress"><div class="progress-bar bg-success" style="width: {{ $s->progressPercent() }}%"></div></div>
                </td>
                <td>
                    <a href="{{ route('students.edit', $s) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('students.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this student?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-muted">No students yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
    {{ $students->links() }}
</div>
@endsection
