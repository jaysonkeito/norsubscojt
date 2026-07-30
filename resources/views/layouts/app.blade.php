<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OJT Tracker') | NORSU Bayawan-Sta. Catalina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        .navbar-brand { font-weight: 700; font-size: 1.35rem; }
        .sidebar { min-height: calc(100vh - 56px); background: #0a3d62; }
        .sidebar .nav-link { color: #d6e8fa; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #125c99; border-radius: 6px; }
        .offcanvas-body .nav-link { color: #d6e8fa; }
        .offcanvas-body .nav-link:hover { color: #fff; background: #125c99; border-radius: 6px; }
        #mobileSidebar { background: #0a3d62; }
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
        .clock-o {
            width: 0.90em;
            height: 0.90em;
            display: inline-block;
            vertical-align: -0.05em;
            margin-right: 1px;
            margin-left: 5px;
            color: white;
        }
        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.15);
            padding: 1rem;
        }
        .sidebar-footer .user-name { color: #d6e8fa; }
        @media (max-width: 767.98px) {
            .card { padding: 1rem !important; }
            h3 { font-size: 1.35rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#0a3d62;">
        <div class="container-fluid">
            @auth
            <button class="btn btn-outline-light d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>
            @endauth
            <a class="navbar-brand flex-grow-1 flex-md-grow-0" href="{{ route('dashboard') }}">
                @include('partials.clock-o')JT Tracker — NORSU BSC
            </a>
        </div>
    </nav>

    @auth
    {{-- Mobile off-canvas sidebar --}}
    <div class="offcanvas offcanvas-start d-md-none d-flex flex-column" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-light" id="mobileSidebarLabel">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between">
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
    @endauth

    <div class="container-fluid">
        <div class="row">
            @auth
            <div class="col-md-2 sidebar py-3 d-none d-md-flex flex-column justify-content-between">
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
            <div class="col-md-10 py-4">
            @else
            <div class="col-12 py-4">
            @endauth

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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>