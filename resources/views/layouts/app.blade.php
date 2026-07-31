<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OJT Tracker') | NORSU Bayawan-Sta. Catalina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        html, body { height: 100%; }
        body { background-color: #f4f6f9; }

        .app-shell { min-height: 100vh; }

        /* Sidebar (desktop, full height, brand at top / footer at bottom) */
        .sidebar {
            width: 250px;
            flex-shrink: 0;
            background: #0a3d62;
        }
        .sidebar-brand {
            display: flex;
            flex-direction: column;
            padding: 1rem;
            color: #fff;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }
        .sidebar-brand:hover { color: #fff; }
        .sidebar-brand .brand-title {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-weight: 700;
            font-size: 1.15rem;
        }
        .sidebar-brand small {
            margin-top: 0.15rem;
            margin-left: 0.5rem;
            font-weight: 400;
            font-size: 0.80rem;
            color: #b8d4ef;
        }
        .sidebar .nav-link { color: #d6e8fa; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #125c99; border-radius: 6px; }
        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.15);
            padding: 1rem;
        }
        .sidebar-footer .user-name { color: #d6e8fa; }

        /* Mobile top bar (hamburger + brand, only shown under md) */
        .mobile-topbar { background-color: #0a3d62; }
        .mobile-topbar .navbar-brand {
            font-weight: 700;
            font-size: 1.15rem;
            padding-top: 0;
            padding-bottom: 0;
            margin-bottom: 0;
            line-height: 1.2;
        }

        /* Mobile off-canvas sidebar reuses the same brand/nav/footer styling */
        #mobileSidebar { background: #0a3d62; }
        .offcanvas-body .nav-link { color: #d6e8fa; }
        .offcanvas-body .nav-link:hover { color: #fff; background: #125c99; border-radius: 6px; }

        .main-content { padding: 1.5rem; flex: 1 1 auto; min-width: 0; }

        .card { border: none; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
        .progress { height: 10px; }
        .table-responsive { -webkit-overflow-scrolling: touch; }
        .btn-success {
            background-color: #0a5aa8;
            border-color: #0a5aa8;
        }
        .btn-success:hover, .btn-success:focus, .btn-success:active {
            background-color: #084a8a !important;
            border-color: #084a8a !important;
        }
        .btn-outline-primary { color: #0a5aa8; border-color: #0a5aa8; }
        .btn-outline-primary:hover { background-color: #0a5aa8; border-color: #0a5aa8; }

        /* Custom password field wrapper — same treatment as the auth layout,
           kept here too in case any interior page ever adds a password field. */
        .password-input-group {
            display: flex;
            align-items: stretch;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            background-color: #fff;
            overflow: hidden;
        }
        .password-input-group:focus-within {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
        }
        .password-input-group .form-control {
            border: none;
            box-shadow: none;
            background-color: transparent;
        }
        .password-input-group .form-control:focus {
            box-shadow: none;
        }
        .password-toggle-btn {
            border: none;
            background-color: transparent;
            color: #6c757d;
        }
        .password-toggle-btn:hover,
        .password-toggle-btn:focus {
            background-color: transparent;
            color: #495057;
            box-shadow: none;
        }
        .eye-btn-hidden {
            visibility: hidden;
        }

        .clock-o {
            width: 0.90em;
            height: 0.90em;
            display: inline-block;
            vertical-align: -0.05em;
            margin-right: -5px;
            margin-left: 7px;
            color: white;
        }
        @media (max-width: 767.98px) {
            .card { padding: 1rem !important; }
            h3 { font-size: 1.35rem; }
            .main-content { padding: 1rem; }
        }
    </style>
</head>
<body>

@auth
    <div class="d-flex app-shell">
        {{-- Desktop sidebar: brand header / nav / footer, full height --}}
        <div class="sidebar d-none d-md-flex flex-column">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <span class="brand-title">@include('partials.clock-o')JT Tracker</span>
                <small>NORSU-BSC</small>
            </a>
            <div class="flex-grow-1 overflow-auto p-2">
                @include('layouts.partials.sidebar-nav')
            </div>
            <div class="sidebar-footer">
                <div class="user-name small mb-2">{{ auth()->user()->name }} <span class="badge bg-secondary">{{ ucfirst(auth()->user()->role) }}</span></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-light w-100" type="submit">Logout</button>
                </form>
            </div>
        </div>

        <div class="flex-grow-1 d-flex flex-column" style="min-width:0;">
            {{-- Mobile-only top bar: just the hamburger + brand, to open the off-canvas sidebar --}}
            <nav class="navbar navbar-expand-lg navbar-dark mobile-topbar d-md-none">
                <div class="container-fluid">
                    <button class="btn btn-outline-light me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Toggle navigation">
                        <i class="bi bi-list"></i>
                    </button>
                    <a class="navbar-brand flex-grow-1" href="{{ route('dashboard') }}">
                        @include('partials.clock-o')JT Tracker — NORSU BSC
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

    {{-- Mobile off-canvas sidebar: same brand/nav/footer structure --}}
    <div class="offcanvas offcanvas-start d-md-none d-flex flex-column" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header border-bottom border-light border-opacity-25">
            <a href="{{ route('dashboard') }}" class="sidebar-brand p-0 border-0" id="mobileSidebarLabel">
                @include('partials.clock-o')JT Tracker
                <small>— NORSU BSC</small>
            </a>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between p-2">
            <div>
                @include('layouts.partials.sidebar-nav')
            </div>
            <div class="sidebar-footer">
                <div class="user-name small mb-2">{{ auth()->user()->name }} <span class="badge bg-secondary">{{ ucfirst(auth()->user()->role) }}</span></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-light w-100" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
@else
    {{-- Guest fallback (rarely hit — login/register use their own layout) --}}
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