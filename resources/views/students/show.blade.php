@extends('layouts.app')
@section('title', 'Student Profile')
@section('content')
<h3 class="mb-4">{{ $student->user->name }} — {{ $student->student_id_no }}</h3>
<div class="card p-4">
    <p><strong>Course:</strong> {{ $student->course }} ({{ $student->year_level }})</p>
    <p><strong>Company:</strong> {{ $student->company->name ?? '—' }}</p>
    <p><strong>Coordinator:</strong> {{ $student->coordinator->name ?? '—' }}</p>
    <p><strong>Progress:</strong> {{ $student->renderedHours() }} / {{ $student->required_hours }} hours ({{ $student->progressPercent() }}%)</p>
</div>
@endsection
