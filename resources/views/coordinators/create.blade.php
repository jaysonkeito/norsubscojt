@extends('layouts.app')
@section('title', 'Add Coordinator')
@section('content')
<h3 class="mb-4">Add OJT Coordinator</h3>
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('coordinators.store') }}">
        @csrf
        <div class="mb-3"><label class="form-label">Full Name</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
        <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
        <button type="submit" class="btn btn-success">Save Coordinator</button>
        <a href="{{ route('coordinators.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection
