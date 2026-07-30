@extends('layouts.app')
@section('title', 'Requirements')
@section('content')
<h3 class="mb-4">Requirements</h3>

@if(auth()->user()->isStudent())
<div class="card p-4 mb-4" style="max-width:600px;">
    <h5>Submit a Requirement</h5>
    <form method="POST" action="{{ route('requirements.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3"><label class="form-label">Document Name</label><input type="text" name="document_name" class="form-control" placeholder="e.g. Endorsement Letter" required></div>
        <div class="mb-3"><label class="form-label">File (optional)</label><input type="file" name="file" class="form-control"></div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
@endif

<div class="card p-3">
    <div class="table-responsive">
<table class="table table-hover">
        <thead><tr>
            @unless(auth()->user()->isStudent())<th>Student</th>@endunless
            <th>Document</th><th>Submitted</th><th>Status</th>
            @unless(auth()->user()->isStudent())<th>Actions</th>@endunless
        </tr></thead>
        <tbody>
        @forelse($requirements as $r)
            <tr>
                @unless(auth()->user()->isStudent())<td>{{ $r->student->user->name ?? '—' }}</td>@endunless
                <td>{{ $r->document_name }}</td>
                <td>{{ $r->submitted_at }}</td>
                <td><span class="badge bg-{{ $r->status === 'approved' ? 'success' : ($r->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($r->status) }}</span></td>
                @unless(auth()->user()->isStudent())
                <td>
                    @if($r->status === 'submitted')
                        <form action="{{ route('requirements.status', $r) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button class="btn btn-sm btn-outline-success">Approve</button>
                        </form>
                        <form action="{{ route('requirements.status', $r) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button class="btn btn-sm btn-outline-danger">Reject</button>
                        </form>
                    @endif
                </td>
                @endunless
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted">No requirements submitted yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
    {{ $requirements->links() ?? '' }}
</div>
@endsection
