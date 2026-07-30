@extends('layouts.app')
@section('title', 'Evaluations')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Evaluations</h3>
    <a href="{{ route('evaluations.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> New Evaluation</a>
</div>
<div class="card p-3">
    <div class="table-responsive">
<table class="table table-hover">
        <thead><tr><th>Student</th><th>Evaluator</th><th>Date</th><th>Total Score</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($evaluations as $e)
            <tr>
                <td>{{ $e->student->user->name ?? '—' }}</td>
                <td>{{ $e->evaluator_name }}</td>
                <td>{{ $e->evaluation_date }}</td>
                <td>{{ $e->total_score }} / 100</td>
                <td>
                    <form action="{{ route('evaluations.destroy', $e) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this evaluation?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted">No evaluations yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
    {{ $evaluations->links() }}
</div>
@endsection
