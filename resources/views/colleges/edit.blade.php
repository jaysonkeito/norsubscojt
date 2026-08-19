@extends('layouts.app')
@section('title', 'Edit College')
@section('content')
<h3 class="mb-4">Edit College — {{ $college->name }}</h3>

{{-- College name edit --}}
<div class="card p-4 mb-4" style="max-width:600px;">
    <form method="POST" action="{{ route('colleges.update', $college) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">College Name</label>
            <input type="text" name="name" class="form-control" value="{{ $college->name }}" required>
        </div>
        <button type="submit" class="btn btn-success">Update College</button>
        <a href="{{ route('colleges.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </form>
</div>

{{-- Programs under this college --}}
<div class="card p-4">
    <h5 class="mb-3">Programs</h5>

    {{-- Add new program --}}
    <form method="POST" action="{{ route('colleges.programs.store', $college) }}" class="mb-4">
        @csrf
        <div class="input-group" style="max-width:500px;">
            <input type="text" name="name" class="form-control" placeholder="e.g. Bachelor of Science in Computer Science" required>
            <button type="submit" class="btn btn-success"><i class="bi bi-plus-lg"></i> Add Program</button>
        </div>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </form>

    {{-- Existing programs --}}
    <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead><tr><th>Program Name</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($college->programs as $program)
            <tr>
                <td>{{ $program->name }}</td>
                <td>
                    <form action="{{ route('colleges.programs.destroy', [$college, $program]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this program?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Remove</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="2" class="text-center text-muted">No programs yet. Add one above.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
