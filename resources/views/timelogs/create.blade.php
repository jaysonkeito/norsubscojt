@extends('layouts.app')
@section('title', 'Record Time')
@section('content')
<h3 class="mb-4">Record Time In / Out</h3>
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('timelogs.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Intern</label>
            <select name="student_id" class="form-select" required>
                <option value="">— Select Intern —</option>
                @foreach($students as $s)
                    <option value="{{ $s->id }}">{{ $s->user->name }} @if($s->student_id_no)({{ $s->student_id_no }})@endif</option>
                @endforeach
            </select>
            @if($students->isEmpty())
                <small class="text-danger">No interns are currently assigned to you.</small>
            @endif
        </div>
        <div class="mb-3"><label class="form-label">Date</label><input type="date" name="log_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Time In</label><input type="time" name="time_in" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Time Out</label><input type="time" name="time_out" class="form-control" required></div>
        </div>
        <div class="mb-3"><label class="form-label">Tasks Performed</label><textarea name="tasks_performed" rows="3" class="form-control"></textarea></div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('timelogs.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection
