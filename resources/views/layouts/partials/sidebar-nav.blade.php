<ul class="nav flex-column">
@php
    $lockedForStudentProfile = auth()->user()->isStudent()
        && auth()->user()->student
        && !auth()->user()->student->isProfileComplete();
    $lockedForCoordinatorProfile = auth()->user()->isCoordinator()
        && auth()->user()->coordinatorProfile
        && !auth()->user()->coordinatorProfile->isProfileComplete();
@endphp
@if($lockedForStudentProfile)
    <li class="nav-item"><a class="nav-link active" href="{{ route('profile.complete') }}"><i class="bi bi-person-lines-fill"></i> Complete Your Profile</a></li>
    <li class="px-3 pt-2"><small class="text-white-50">Finish your profile to unlock the rest of the menu.</small></li>
@elseif($lockedForCoordinatorProfile)
    <li class="nav-item"><a class="nav-link active" href="{{ route('coordinator-profile.complete') }}"><i class="bi bi-person-lines-fill"></i> Complete Your Profile</a></li>
    <li class="px-3 pt-2"><small class="text-white-50">Finish your profile to unlock the rest of the menu.</small></li>
@else
    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
    @if(auth()->user()->role === 'admin')
    <li class="nav-item"><a class="nav-link" href="{{ route('coordinators.index') }}"><i class="bi bi-person-badge"></i> OJT Coordinators</a></li>
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center" href="{{ route('pending-approvals.index') }}">
            <i class="bi bi-person-check"></i> <span class="ms-1">Pending Approvals</span>
            @php($pendingCount = \App\Models\User::where('status', 'pending')->count())
            @if($pendingCount > 0)
                <span class="badge bg-danger ms-auto">{{ $pendingCount }}</span>
            @endif
        </a>
    </li>
    @endif
    @if(in_array(auth()->user()->role, ['admin', 'coordinator']))
    <li class="nav-item"><a class="nav-link" href="{{ route('students.index') }}"><i class="bi bi-people-fill"></i> Interns</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('companies.index') }}"><i class="bi bi-building"></i> Offices/Companies</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('evaluations.index') }}"><i class="bi bi-clipboard-check"></i> Evaluations</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('reports.index') }}"><i class="bi bi-graph-up"></i> Reports</a></li>
    @endif
    <li class="nav-item"><a class="nav-link" href="{{ route('timelogs.index') }}"><i class="bi bi-clock-history"></i> Time Logs (Attendance)</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('requirements.index') }}"><i class="bi bi-file-earmark-text"></i> Requirements</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('announcements.index') }}"><i class="bi bi-megaphone"></i> Announcements</a></li>
@endif
</ul>
