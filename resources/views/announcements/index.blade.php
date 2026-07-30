@extends('layouts.app')
@section('title', 'Announcements')
@section('content')
<h3 class="mb-4">Announcements</h3>

@if(auth()->user()->isAdmin())
<div class="card p-4 mb-4">
    <h5>Post New Announcement</h5>
    <form method="POST" action="{{ route('announcements.store') }}">
        @csrf
        <div class="mb-3"><label class="form-label">Title</label><input type="text" name="title" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Content</label><textarea name="content" rows="3" class="form-control" required></textarea></div>
        <button type="submit" class="btn btn-success">Post</button>
    </form>
</div>
@endif

@forelse($announcements as $a)
    <div class="card p-3 mb-3">
        <div class="d-flex justify-content-between">
            <h5>{{ $a->title }}</h5>
            @if(auth()->user()->isAdmin())
            <form action="{{ route('announcements.destroy', $a) }}" method="POST" onsubmit="return confirm('Delete this announcement?');">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
            @endif
        </div>
        <p class="mb-1">{{ $a->content }}</p>
        <small class="text-muted">Posted by {{ $a->poster->name ?? 'Unknown' }} on {{ $a->created_at->format('M d, Y') }}</small>
    </div>
@empty
    <p class="text-muted">No announcements yet.</p>
@endforelse
{{ $announcements->links() }}
@endsection
