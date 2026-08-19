<ul class="nav flex-column">
@php
    $lockedForUnassigned = auth()->user()->isUnassigned();
    $lockedForStudentProfile = auth()->user()->isStudent()
        && auth()->user()->student
        && !auth()->user()->student->isProfileComplete();
    $lockedForCoordinatorProfile = auth()->user()->isCoordinator()
        && auth()->user()->coordinatorProfile
        && !auth()->user()->coordinatorProfile->isProfileComplete();
    $lockedForCompanyProfile = auth()->user()->isCompany()
        && auth()->user()->companyProfile
        && !auth()->user()->companyProfile->isProfileComplete();
@endphp
@if($lockedForUnassigned)
    <li class="nav-item"><a class="nav-link active" href="{{ route('account-completion.show') }}"><i class="bi bi-person-lines-fill"></i> Complete Your Account</a></li>
    <li class="px-3 pt-2"><small class="text-white-50">Pick your role to unlock the rest of the menu.</small></li>
@elseif(auth()->user()->isPending())
    <li class="nav-item"><a class="nav-link active" href="{{ route('account-pending.show') }}"><i class="bi bi-hourglass-split"></i> Pending Approval</a></li>
    <li class="px-3 pt-2"><small class="text-white-50">Waiting for approval — check back later.</small></li>
@elseif($lockedForStudentProfile)
    <li class="nav-item"><a class="nav-link active" href="{{ route('profile.complete') }}"><i class="bi bi-person-lines-fill"></i> Complete Your Profile</a></li>
    <li class="px-3 pt-2"><small class="text-white-50">Finish your profile to unlock the rest of the menu.</small></li>
@elseif($lockedForCoordinatorProfile)
    <li class="nav-item"><a class="nav-link active" href="{{ route('coordinator-profile.complete') }}"><i class="bi bi-person-lines-fill"></i> Complete Your Profile</a></li>
    <li class="px-3 pt-2"><small class="text-white-50">Finish your profile to unlock the rest of the menu.</small></li>
@elseif($lockedForCompanyProfile)
    <li class="nav-item"><a class="nav-link active" href="{{ route('company-profile.complete') }}"><i class="bi bi-person-lines-fill"></i> Complete Your Profile</a></li>
    <li class="px-3 pt-2"><small class="text-white-50">Finish your profile to unlock the rest of the menu.</small></li>
@else
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
    @if(auth()->user()->role === 'admin')
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('coordinators.*') ? 'active' : '' }}" href="{{ route('coordinators.index') }}"><i class="bi bi-person-badge"></i> OJT Coordinators</a></li>
    @endif
    @if(in_array(auth()->user()->role, ['admin', 'dean']))
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center {{ request()->routeIs('pending-approvals.*') ? 'active' : '' }}" href="{{ route('pending-approvals.index') }}">
            <i class="bi bi-person-check"></i> <span class="ms-1">Pending Approvals</span>
            @php
                $pendingQuery = \App\Models\User::where('status', 'pending');
                if (auth()->user()->isDean()) {
                    $pendingQuery->whereIn('role', ['coordinator', 'company']);
                }
                $pendingCount = $pendingQuery->count();
            @endphp
            @if($pendingCount > 0)
                <span class="badge bg-danger ms-auto">{{ $pendingCount }}</span>
            @endif
        </a>
    </li>
    @endif
    @if(in_array(auth()->user()->role, ['admin', 'coordinator', 'dean']))
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}"><i class="bi bi-people-fill"></i> Interns</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}" href="{{ route('companies.index') }}"><i class="bi bi-building"></i> Offices/Companies</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}"><i class="bi bi-graph-up"></i> Reports</a></li>
    @endif
    @if(in_array(auth()->user()->role, ['admin', 'coordinator', 'dean', 'company']))
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('evaluations.*') ? 'active' : '' }}" href="{{ route('evaluations.index') }}"><i class="bi bi-clipboard-check"></i> Evaluations</a></li>
    @endif
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}"><i class="bi bi-person-circle"></i> My Profile</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('timelogs.*') ? 'active' : '' }}" href="{{ route('timelogs.index') }}"><i class="bi bi-clock-history"></i> Time Logs (Attendance)</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('requirements.*') ? 'active' : '' }}" href="{{ route('requirements.index') }}"><i class="bi bi-file-earmark-text"></i> Requirements</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}" href="{{ route('announcements.index') }}"><i class="bi bi-megaphone"></i> Announcements</a></li>
@endif
</ul>
