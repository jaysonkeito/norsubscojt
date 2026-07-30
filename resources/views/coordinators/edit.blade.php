@extends('layouts.app')
@section('title', 'Edit Coordinator')
@section('content')
<h3 class="mb-4">Edit Coordinator — {{ $coordinator->name }}</h3>
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('coordinators.update', $coordinator) }}">
        @csrf @method('PUT')
        <div class="mb-3"><label class="form-label">Full Name</label><input type="text" name="name" class="form-control" value="{{ $coordinator->name }}" required></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ $coordinator->email }}" required></div>
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
        </div>
        <button type="submit" class="btn btn-success">Update Coordinator</button>
        <a href="{{ route('coordinators.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection
