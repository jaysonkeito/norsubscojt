@extends('layouts.app')
@section('title', 'Add Company')
@section('content')
<h3 class="mb-4">Add Company</h3>
<div class="card p-4">
    <form method="POST" action="{{ route('companies.store') }}">
        @csrf
        <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
        <div class="mb-3">
            <label class="form-label">Affiliation Type</label>
            <select name="affiliation_type" class="form-select">
                <option value="">— Not specified —</option>
                <option value="inside_campus">Inside Campus (NORSU-BSC Office)</option>
                <option value="outside_campus">Outside Campus</option>
            </select>
        </div>
        <div class="mb-3"><label class="form-label">Address</label><input type="text" name="address" class="form-control"></div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Contact Person</label><input type="text" name="contact_person" class="form-control"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-control"></div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Industry</label><input type="text" name="industry" class="form-control"></div>
        </div>
        <div class="mb-3">
            <label class="form-label">MOA Status</label>
            <select name="moa_status" class="form-select" required>
                <option value="none">None</option>
                <option value="pending">Pending</option>
                <option value="active">Active</option>
                <option value="expired">Expired</option>
            </select>
        </div>

        <hr>
        <h6>Host Company Login (optional)</h6>
        <p class="text-muted small">Fill these in to let this company log in and view/approve their own interns' time logs.</p>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Login Email</label><input type="email" name="login_email" class="form-control"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Login Password</label><input type="text" name="login_password" class="form-control"></div>
        </div>

        <button type="submit" class="btn btn-success">Save Company</button>
        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection
