@extends('layouts.app')
@section('title', 'Edit Student')
@section('content')
<h3 class="mb-4">Edit Student — {{ $student->user->name }}</h3>
<div class="card p-4">
    <form method="POST" action="{{ route('students.update', $student) }}">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Course</label><input type="text" name="course" class="form-control" value="{{ $student->course }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Year Level</label><input type="text" name="year_level" class="form-control" value="{{ $student->year_level }}" required></div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-control" value="{{ $student->contact_number }}"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Address</label><input type="text" name="address" class="form-control" value="{{ $student->address }}"></div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Company</label>
                <select name="company_id" class="form-select">
                    <option value="">— Not Assigned —</option>
                    @foreach($companies as $c)<option value="{{ $c->id }}" @selected($student->company_id == $c->id)>{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Coordinator</label>
                <select name="coordinator_id" class="form-select">
                    <option value="">— Not Assigned —</option>
                    @foreach($coordinators as $c)<option value="{{ $c->id }}" @selected($student->coordinator_id == $c->id)>{{ $c->name }}</option>@endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Required Hours</label><input type="number" name="required_hours" class="form-control" value="{{ $student->required_hours }}" required></div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="not_deployed" @selected($student->status=='not_deployed')>Not Deployed</option>
                    <option value="deployed" @selected($student->status=='deployed')>Deployed</option>
                    <option value="completed" @selected($student->status=='completed')>Completed</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Update Student</button>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection
