@extends('layouts.app')
@section('title', 'Log Time')
@section('content')
<h3 class="mb-4">Log Time In / Out</h3>
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('timelogs.store') }}">
        @csrf
        <div class="mb-3"><label class="form-label">Date</label><input type="date" name="log_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Time In</label><input type="time" name="time_in" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Time Out</label><input type="time" name="time_out" class="form-control" required></div>
        </div>
        <div class="mb-3"><label class="form-label">Tasks Performed</label><textarea name="tasks_performed" rows="3" class="form-control"></textarea></div>
        <button type="submit" class="btn btn-success">Submit</button>
        <a href="{{ route('timelogs.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection
