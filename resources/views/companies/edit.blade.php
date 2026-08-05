@extends('layouts.app')
@section('title', 'Edit Company')
@section('content')
<h3 class="mb-4">Edit Company — {{ $company->name }}</h3>
<div class="card p-4">
    <form method="POST" action="{{ route('companies.update', $company) }}">
        @csrf @method('PUT')
        <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" value="{{ $company->name }}" required></div>
        <div class="mb-3">
            <label class="form-label">Affiliation Type</label>
            <select name="affiliation_type" class="form-select">
                <option value="">— Not specified —</option>
                <option value="inside_campus" {{ $company->affiliation_type === 'inside_campus' ? 'selected' : '' }}>Inside Campus (NORSU-BSC Office)</option>
                <option value="outside_campus" {{ $company->affiliation_type === 'outside_campus' ? 'selected' : '' }}>Outside Campus</option>
            </select>
        </div>
        <div class="mb-3"><label class="form-label">Address</label><input type="text" name="address" class="form-control" value="{{ $company->address }}"></div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Contact Person</label><input type="text" name="contact_person" class="form-control" value="{{ $company->contact_person }}"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-control" value="{{ $company->contact_number }}"></div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ $company->email }}"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Industry</label><input type="text" name="industry" class="form-control" value="{{ $company->industry }}"></div>
        </div>
        <div class="mb-3">
            <label class="form-label">MOA Status</label>
            <select name="moa_status" class="form-select" required>
                <option value="none" @selected($company->moa_status=='none')>None</option>
                <option value="pending" @selected($company->moa_status=='pending')>Pending</option>
                <option value="active" @selected($company->moa_status=='active')>Active</option>
                <option value="expired" @selected($company->moa_status=='expired')>Expired</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update Company</button>
        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection
