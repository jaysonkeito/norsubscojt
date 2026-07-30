@extends('layouts.app')
@section('title', 'New Evaluation')
@section('content')
<h3 class="mb-4">New Evaluation</h3>
<div class="card p-4" style="max-width:700px;">
    <form method="POST" action="{{ route('evaluations.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Student</label>
            <select name="student_id" class="form-select" required>
                <option value="">— Select Student —</option>
                @foreach($students as $s)<option value="{{ $s->id }}">{{ $s->user->name }} ({{ $s->student_id_no }})</option>@endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Evaluator Name</label><input type="text" name="evaluator_name" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Evaluation Date</label><input type="date" name="evaluation_date" class="form-control" required></div>
        </div>
        <p class="text-muted small">Score each criterion from 0–20.</p>
        <div class="row">
            <div class="col-md-4 mb-3"><label class="form-label">Attendance</label><input type="number" name="attendance_score" min="0" max="20" class="form-control" required></div>
            <div class="col-md-4 mb-3"><label class="form-label">Work Quality</label><input type="number" name="work_quality_score" min="0" max="20" class="form-control" required></div>
            <div class="col-md-4 mb-3"><label class="form-label">Attitude</label><input type="number" name="attitude_score" min="0" max="20" class="form-control" required></div>
            <div class="col-md-4 mb-3"><label class="form-label">Initiative</label><input type="number" name="initiative_score" min="0" max="20" class="form-control" required></div>
            <div class="col-md-4 mb-3"><label class="form-label">Communication</label><input type="number" name="communication_score" min="0" max="20" class="form-control" required></div>
        </div>
        <div class="mb-3"><label class="form-label">Comments</label><textarea name="comments" rows="3" class="form-control"></textarea></div>
        <button type="submit" class="btn btn-success">Save Evaluation</button>
        <a href="{{ route('evaluations.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection
