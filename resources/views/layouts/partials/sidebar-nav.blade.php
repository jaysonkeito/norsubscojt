<ul class="nav flex-column">
    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
    @if(auth()->user()->role === 'admin')
    <li class="nav-item"><a class="nav-link" href="{{ route('coordinators.index') }}"><i class="bi bi-person-badge"></i> OJT Coordinators</a></li>
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
</ul>
