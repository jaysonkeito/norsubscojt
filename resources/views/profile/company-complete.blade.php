@extends('layouts.app')
@section('title', 'Complete Your Profile')
@section('content')
<h3 class="mb-1">Complete Your Profile</h3>
<p class="text-muted mb-4">Just a couple of contact details so Admin/Dean can reach you if needed. You won't be able to use the rest of the system until this is saved.</p>

<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('company-profile.complete.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Mobile Number</label>
            <input type="text" name="mobile_number" class="form-control" value="{{ old('mobile_number', $profile->mobile_number) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Office Landline <span class="text-muted">(optional)</span></label>
            <input type="text" name="office_landline" class="form-control" value="{{ old('office_landline', $profile->office_landline) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">ID / Badge Number <span class="text-muted">(optional)</span></label>
            <input type="text" name="id_badge_number" class="form-control" value="{{ old('id_badge_number', $profile->id_badge_number) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Alternate Email <span class="text-muted">(optional)</span></label>
            <input type="email" name="alternate_email" class="form-control" value="{{ old('alternate_email', $profile->alternate_email) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Photo <span class="text-muted">(optional)</span></label>
            <input type="file" name="photo" accept="image/*" class="form-control">
        </div>

        <button type="submit" class="btn btn-success mt-2">Save &amp; Continue to Dashboard</button>
    </form>
</div>
@endsection
