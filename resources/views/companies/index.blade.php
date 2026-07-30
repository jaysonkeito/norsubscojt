@extends('layouts.app')
@section('title', 'Companies')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Offices/Companies</h3>
    <a href="{{ route('companies.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Add Company</a>
</div>
<div class="card p-3">
    <div class="table-responsive">
<table class="table table-hover">
        <thead><tr><th>Name</th><th>Industry</th><th>Contact Person</th><th>MOA Status</th><th># Students</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($companies as $c)
            <tr>
                <td>{{ $c->name }}</td>
                <td>{{ $c->industry ?? '—' }}</td>
                <td>{{ $c->contact_person ?? '—' }}</td>
                <td><span class="badge bg-info text-dark">{{ ucfirst($c->moa_status) }}</span></td>
                <td>{{ $c->students_count }}</td>
                <td>
                    <a href="{{ route('companies.edit', $c) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('companies.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this company?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted">No companies yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
    {{ $companies->links() }}
</div>
@endsection
