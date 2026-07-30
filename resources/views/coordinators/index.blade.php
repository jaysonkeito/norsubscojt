@extends('layouts.app')
@section('title', 'OJT Coordinators')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h3 class="mb-0">OJT Coordinators</h3>
    <a href="{{ route('coordinators.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Add Coordinator</a>
</div>
<div class="card p-3">
    <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead><tr><th>Name</th><th>Email</th><th># Interns Advised</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($coordinators as $c)
            <tr>
                <td>{{ $c->name }}</td>
                <td>{{ $c->email }}</td>
                <td>{{ $c->students_advised_count }}</td>
                <td>
                    <a href="{{ route('coordinators.edit', $c) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('coordinators.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this coordinator? Their advisees will become unassigned.');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">No coordinators yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
    {{ $coordinators->links() }}
</div>
@endsection
