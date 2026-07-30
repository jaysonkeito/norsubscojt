@extends('layouts.app')
@section('title', 'Add Student')
@section('content')
<h3 class="mb-4">Add Student</h3>
<div class="card p-4">
    <form method="POST" action="{{ route('students.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Full Name</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Email (optional)</label><input type="email" name="email" class="form-control" value="{{ old('email') }}"></div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Password (optional)</label>
                <input type="text" name="password" class="form-control" placeholder="Leave blank to auto-generate">
                <small class="text-muted">Interns log in with their Student ID. If left blank, the password defaults to their last name (lowercase).</small>
            </div>
            <div class="col-md-6 mb-3"><label class="form-label">Student ID No.</label><input type="text" name="student_id_no" class="form-control" value="{{ old('student_id_no') }}" required></div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Course</label><input type="text" name="course" class="form-control" value="{{ old('course') }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Year Level</label><input type="text" name="year_level" class="form-control" value="{{ old('year_level') }}" required></div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-control"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Address</label><input type="text" name="address" class="form-control"></div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Company</label>
                <select name="company_id" class="form-select">
                    <option value="">— Not Assigned —</option>
                    @foreach($companies as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Coordinator</label>
                <select name="coordinator_id" class="form-select">
                    <option value="">— Not Assigned —</option>
                    @foreach($coordinators as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Required Hours</label><input type="number" name="required_hours" class="form-control" value="500" required></div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="not_deployed">Not Deployed</option>
                    <option value="deployed">Deployed</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Save Student</button>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection
