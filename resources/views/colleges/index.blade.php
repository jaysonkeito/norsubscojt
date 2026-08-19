@extends('layouts.app')
@section('title', 'Colleges & Programs')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h3 class="mb-0">Colleges &amp; Programs</h3>
    <a href="{{ route('colleges.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Add College</a>
</div>
<div class="card p-3">
    <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead><tr><th>College Name</th><th>Programs</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($colleges as $college)
            <tr>
                <td>{{ $college->name }}</td>
                <td><span class="badge bg-secondary">{{ $college->programs_count }}</span></td>
                <td>
                    <a href="{{ route('colleges.edit', $college) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('colleges.destroy', $college) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this college and all its programs?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="text-center text-muted">No colleges yet. Add one to get started.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
