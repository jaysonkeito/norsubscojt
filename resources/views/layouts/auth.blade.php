<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OJT Tracker') | NORSU Bayawan-Sta. Catalina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body.auth-body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
            /*background: linear-gradient(135deg, #062c52 0%, #0a4a8f 45%, #1f7fc9 75%, #6fb7ea 100%);*/
            background: linear-gradient(rgba(6,44,82,0.75), rgba(10,74,143,0.65)), url('{{ asset('images/campus-bg.jpg') }}') center/cover no-repeat;
            background-attachment: fixed;
        }
        /* Soft dotted texture overlay for depth, in place of a photo */
        body.auth-body::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.10) 1.5px, transparent 1.5px);
            background-size: 26px 26px;
            pointer-events: none;
        }
        /* Campus skyline silhouette, authored as inline SVG (no external image dependency) */
        .auth-skyline {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 22vh;
            min-height: 140px;
            opacity: 0.35;
            pointer-events: none;
        }
        .auth-card-wrap {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 760px;
        }
        .auth-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 16px;
            box-shadow: 0 20px 45px rgba(3, 25, 51, 0.35);
            border: none;
            max-width: 460px;
            margin: 0 auto;
        }
        .auth-card.wide { max-width: 760px; }
        .auth-brand-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, #0a4a8f, #2d95d6);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem auto;
            box-shadow: 0 6px 16px rgba(10, 74, 143, 0.35);
        }
        .auth-brand-icon i { color: #fff; font-size: 1.6rem; }
        .btn-azure {
            background-color: #0a5aa8;
            border-color: #0a5aa8;
            color: #fff;
        }
        .btn-azure:hover, .btn-azure:focus {
            background-color: #084a8a;
            border-color: #084a8a;
            color: #fff;
        }

        /* Custom password field wrapper — one continuous border around
           an input + toggle button, so it always reads as a single field
           regardless of whether the eye icon is visible or hidden. */
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

        /* Icon-suffix input (e.g. the Email/Student ID field on Login) */
        .icon-input-group {
            position: relative;
        }
        .icon-input-group .form-control {
            padding-right: 2.5rem;
        }
        .icon-input-suffix {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            pointer-events: none;
        }

        /* "or" divider between password login and Google buttons */
        .auth-divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #adb5bd;
            font-size: 0.85rem;
        }
        .auth-divider::before,
        .auth-divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }
        .auth-divider span {
            padding: 0 0.75rem;
        }

        /* Google sign-in buttons */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background-color: #fff;
            border: 1px solid #dadce0;
            color: #3c4043;
            font-weight: 500;
        }
        .btn-google:hover {
            background-color: #f8f9fa;
            color: #3c4043;
            border-color: #dadce0;
        }
        .btn-google i {
            color: #4285F4;
        }

        /* Segmented role toggle on Register (btn-check + label pattern) */
        .role-toggle .btn-outline-azure {
            color: #0a5aa8;
            border-color: #0a5aa8;
            background-color: #fff;
        }
        .role-toggle .btn-check:checked + .btn-outline-azure {
            background-color: #0a5aa8;
            border-color: #0a5aa8;
            color: #fff;
        }
        .auth-card a { color: #0a5aa8; text-decoration: none; font-weight: 500; }
        .auth-card a:hover { text-decoration: underline; }
        .clock-o {
            width: 0.78em;
            height: 0.78em;
            display: inline-block;
            vertical-align: -0.05em;
            margin-right: 1px;
            color: black;
        }
    </style>
</head>
<body class="auth-body">

    <!--<svg class="auth-skyline" viewBox="0 0 1200 200" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="0" y="120" width="60" height="80" fill="#ffffff"/>
        <rect x="70" y="90" width="45" height="110" fill="#ffffff"/>
        <rect x="125" y="140" width="70" height="60" fill="#ffffff"/>
        <rect x="205" y="70" width="55" height="130" fill="#ffffff"/>
        <polygon points="232,40 210,70 254,70" fill="#ffffff"/>
        <rect x="270" y="110" width="40" height="90" fill="#ffffff"/>
        <rect x="320" y="150" width="90" height="50" fill="#ffffff"/>
        <rect x="420" y="95" width="50" height="105" fill="#ffffff"/>
        <rect x="480" y="130" width="60" height="70" fill="#ffffff"/>
        <rect x="550" y="80" width="45" height="120" fill="#ffffff"/>
        <polygon points="572,50 550,80 594,80" fill="#ffffff"/>
        <rect x="605" y="125" width="80" height="75" fill="#ffffff"/>
        <rect x="695" y="100" width="50" height="100" fill="#ffffff"/>
        <rect x="755" y="145" width="65" height="55" fill="#ffffff"/>
        <rect x="830" y="85" width="55" height="115" fill="#ffffff"/>
        <rect x="895" y="120" width="45" height="80" fill="#ffffff"/>
        <rect x="950" y="150" width="90" height="50" fill="#ffffff"/>
        <rect x="1050" y="100" width="50" height="100" fill="#ffffff"/>
        <rect x="1110" y="135" width="60" height="65" fill="#ffffff"/>
    </svg>-->

    <div class="auth-card-wrap">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
