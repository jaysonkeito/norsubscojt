<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OJT Tracker') | NORSU Bayawan-Sta. Catalina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0F172A;
            --navy-soft: #1E293B;
            --electric-blue: #3B82F6;
            --electric-blue-dark: #2563EB;
            --electric-blue-tint: #EFF6FF;
            --border-soft: #E2E8F0;
            --text-muted: #64748B;
        }
        html, body { height: 100%; }
        body {
            background-color: #F8FAFC;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--navy);
        }
        h1, h2, h3, h4, h5, h6 { font-weight: 700; color: var(--navy); letter-spacing: -0.01em; }

        .app-shell { min-height: 100vh; }

        /* ===== Sidebar ===== */
        .sidebar {
            width: 260px;
            flex-shrink: 0;
            background: var(--navy);
        }
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 1.25rem 1.25rem 1rem 1.25rem;
        }
        .sidebar-brand-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: var(--electric-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-brand-icon .clock-o { color: #fff; width: 1em; height: 1em; margin: 0; }
        .sidebar-brand .brand-title {
            color: #fff;
            font-weight: 700;
            font-size: 1.05rem;
            line-height: 1.15;
        }
        .sidebar-brand small {
            display: block;
            color: #94A3B8;
            font-weight: 400;
            font-size: 0.68rem;
        }
        .sidebar-nav-wrap { padding: 0.5rem 0.9rem; }
        .sidebar .nav-link {
            color: #CBD5E1;
            font-size: 0.92rem;
            font-weight: 500;
            border-radius: 10px;
            padding: 0.6rem 0.85rem;
            margin-bottom: 0.15rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            transition: background-color .15s ease, color .15s ease;
        }
        .sidebar .nav-link i { font-size: 1.05rem; width: 1.1em; text-align: center; }
        .sidebar .nav-link:hover { color: #fff; background: var(--navy-soft); }
        .sidebar .nav-link.active {
            color: #fff;
            background: var(--electric-blue);
            box-shadow: 0 2px 8px rgba(59,130,246,0.35);
        }
        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.08);
            padding: 1rem 1.25rem;
        }
        .sidebar-footer .user-name { color: #E2E8F0; font-weight: 500; }
        .sidebar-footer .badge { background: var(--navy-soft) !important; font-weight: 500; }
        .sidebar-footer .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.15);
            flex-shrink: 0;
        }
        .sidebar-footer .user-avatar-placeholder {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--navy-soft);
            border: 2px solid rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94A3B8;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* Mobile top bar */
        .mobile-topbar { background-color: var(--navy); }
        .mobile-topbar .navbar-brand {
            font-weight: 700;
            font-size: 1.05rem;
            padding-top: 0;
            padding-bottom: 0;
            margin-bottom: 0;
            line-height: 1.2;
            color: #fff;
        }

        #mobileSidebar { background: var(--navy); }
        .offcanvas-body .nav-link { color: #CBD5E1; }
        .offcanvas-body .nav-link:hover { color: #fff; background: var(--navy-soft); border-radius: 10px; }
        .offcanvas-body .nav-link.active { color: #fff; background: var(--electric-blue); border-radius: 10px; }

        .main-content { padding: 2rem; flex: 1 1 auto; min-width: 0; }

        /* ===== Cards ===== */
        .card {
            border: 1px solid var(--border-soft);
            border-radius: 14px;
            box-shadow: 0 1px 2px rgba(15,23,42,0.04), 0 1px 6px rgba(15,23,42,0.04);
        }
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--electric-blue-tint);
            color: var(--electric-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .progress { height: 8px; border-radius: 6px; background-color: #EEF2F7; }
        .progress-bar.bg-success { background-color: var(--electric-blue) !important; }
        .table-responsive { -webkit-overflow-scrolling: touch; }
        .table thead th { color: var(--text-muted); font-weight: 600; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.03em; border-bottom-width: 1px; }

        /* ===== Buttons ===== */
        .btn { border-radius: 10px; font-weight: 500; }
        .btn-success, .btn-primary {
            background-color: var(--electric-blue);
            border-color: var(--electric-blue);
        }
        .btn-success:hover, .btn-success:focus, .btn-success:active,
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--electric-blue-dark) !important;
            border-color: var(--electric-blue-dark) !important;
        }
        .btn-outline-primary { color: var(--electric-blue); border-color: var(--electric-blue); }
        .btn-outline-primary:hover { background-color: var(--electric-blue); border-color: var(--electric-blue); }
        .btn-outline-secondary { border-color: var(--border-soft); color: var(--text-muted); }

        .form-control, .form-select {
            border-radius: 10px;
            border-color: var(--border-soft);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--electric-blue);
            box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.15);
        }

        /* Password field wrapper */
        .password-input-group {
            display: flex;
            align-items: stretch;
            border: 1px solid var(--border-soft);
            border-radius: 10px;
            background-color: #fff;
            overflow: hidden;
        }
        .password-input-group:focus-within {
            border-color: var(--electric-blue);
            box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.15);
        }
        .password-input-group .form-control { border: none; box-shadow: none; background-color: transparent; }
        .password-input-group .form-control:focus { box-shadow: none; }
        .password-toggle-btn { border: none; background-color: transparent; color: var(--text-muted); }
        .password-toggle-btn:hover, .password-toggle-btn:focus { background-color: transparent; color: var(--navy); box-shadow: none; }
        .eye-btn-hidden { visibility: hidden; }

        .clock-o { width: 0.90em; height: 0.90em; display: inline-block; vertical-align: -0.05em; margin-right: -5px; margin-left: 7px; color: white; }

        .badge { border-radius: 6px; font-weight: 500; }

        @media (max-width: 767.98px) {
            .card { padding: 1rem !important; }
            h3 { font-size: 1.3rem; }
            .main-content { padding: 1.25rem; }
        }
    </style>
</head>
<body>

@auth
    <div class="d-flex app-shell">
        <div class="sidebar d-none d-md-flex flex-column">
            <a href="{{ route('dashboard') }}" class="sidebar-brand text-decoration-none">
                <div class="sidebar-brand-icon">@include('partials.clock-o')</div>
                <div>
                    <span class="brand-title">OJT Tracker</span>
                    <small>NORSU Bayawan-Sta. Catalina</small>
                </div>
            </a>
            <div class="sidebar-nav-wrap flex-grow-1 overflow-auto">
                @include('layouts.partials.sidebar-nav')
            </div>
            <div class="sidebar-footer">
                <div class="d-flex align-items-center gap-2 mb-2">
                    @php
                        $photoUrl = null;
                        if (auth()->user()->isStudent() && auth()->user()->student?->photo_path) {
                            $photoUrl = route('files.student-photo', auth()->user()->student);
                        } elseif (auth()->user()->isCoordinator() && auth()->user()->coordinatorProfile?->photo_path) {
                            $photoUrl = route('files.coordinator-photo', auth()->user());
                        } elseif (auth()->user()->isCompany() && auth()->user()->companyProfile?->photo_path) {
                            $photoUrl = route('files.company-photo', auth()->user());
                        } elseif (auth()->user()->photo_path) {
                            $photoUrl = route('files.user-photo', auth()->user());
                        }
                    @endphp
                    @if($photoUrl)
                        <img src="{{ $photoUrl }}" alt="Profile" class="user-avatar">
                    @else
                        <div class="user-avatar-placeholder"><i class="bi bi-person-fill"></i></div>
                    @endif
                    <div class="d-flex flex-column overflow-hidden">
                        <span class="user-name small text-truncate" style="max-width:140px;">{{ auth()->user()->name }}</span>
                        <span class="badge" style="width:fit-content;">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-light w-100" type="submit">Logout</button>
                </form>
            </div>
        </div>

        <div class="flex-grow-1 d-flex flex-column" style="min-width:0;">
            <nav class="navbar navbar-expand-lg navbar-dark mobile-topbar d-md-none">
                <div class="container-fluid">
                    <button class="btn btn-outline-light me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Toggle navigation">
                        <i class="bi bi-list"></i>
                    </button>
                    <a class="navbar-brand flex-grow-1 d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                        <span class="sidebar-brand-icon" style="width:28px;height:28px;">@include('partials.clock-o')</span>
                        OJT Tracker
                    </a>
                </div>
            </nav>

            <main class="main-content">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <div class="offcanvas offcanvas-start d-md-none d-flex flex-column" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header border-bottom border-light border-opacity-25">
            <a href="{{ route('dashboard') }}" class="sidebar-brand p-0 border-0" id="mobileSidebarLabel">
                <div class="sidebar-brand-icon">@include('partials.clock-o')</div>
                <div>
                    <span class="brand-title">OJT Tracker</span>
                    <small>NORSU BSC</small>
                </div>
            </a>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between p-3">
            <div>
                @include('layouts.partials.sidebar-nav')
            </div>
            <div class="sidebar-footer">
                <div class="d-flex align-items-center gap-2 mb-2">
                    @php
                        $photoUrl = null;
                        if (auth()->user()->isStudent() && auth()->user()->student?->photo_path) {
                            $photoUrl = route('files.student-photo', auth()->user()->student);
                        } elseif (auth()->user()->isCoordinator() && auth()->user()->coordinatorProfile?->photo_path) {
                            $photoUrl = route('files.coordinator-photo', auth()->user());
                        } elseif (auth()->user()->isCompany() && auth()->user()->companyProfile?->photo_path) {
                            $photoUrl = route('files.company-photo', auth()->user());
                        } elseif (auth()->user()->photo_path) {
                            $photoUrl = route('files.user-photo', auth()->user());
                        }
                    @endphp
                    @if($photoUrl)
                        <img src="{{ $photoUrl }}" alt="Profile" class="user-avatar">
                    @else
                        <div class="user-avatar-placeholder"><i class="bi bi-person-fill"></i></div>
                    @endif
                    <div class="d-flex flex-column overflow-hidden">
                        <span class="user-name small text-truncate" style="max-width:140px;">{{ auth()->user()->name }}</span>
                        <span class="badge" style="width:fit-content;">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-light w-100" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
@else
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
