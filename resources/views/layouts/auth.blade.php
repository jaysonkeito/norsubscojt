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
            --electric-blue: #3B82F6;
            --electric-blue-dark: #2563EB;
            --border-soft: #E2E8F0;
            --text-muted: #64748B;
        }
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--navy);
        }
        h1, h2, h3, h4, h5, h6 { font-weight: 700; color: var(--navy); letter-spacing: -0.01em; }

        body.auth-body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
            background: #F8FAFC;
        }
        /* Faint dot-grid texture, subtle enough not to compete with the card */
        body.auth-body::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(15,23,42,0.05) 1.2px, transparent 1.2px);
            background-size: 24px 24px;
            pointer-events: none;
        }
        /* Soft electric-blue glow in the corner for depth without a photo */
        body.auth-body::after {
            content: "";
            position: absolute;
            top: -120px;
            right: -120px;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59,130,246,0.14) 0%, rgba(59,130,246,0) 70%);
            pointer-events: none;
        }

        .auth-card-wrap {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 760px;
        }
        .auth-card {
            background: #fff;
            border-radius: 18px;
            border: 1px solid var(--border-soft);
            box-shadow: 0 1px 2px rgba(15,23,42,0.04), 0 12px 32px rgba(15,23,42,0.08);
            max-width: 460px;
            margin: 0 auto;
        }
        .auth-card.wide { max-width: 760px; }
        .auth-brand-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: var(--electric-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.9rem auto;
        }
        .auth-brand-icon i { color: #fff; font-size: 1.5rem; }
        .btn { border-radius: 10px; font-weight: 500; }
        .btn-azure {
            background-color: var(--electric-blue);
            border-color: var(--electric-blue);
            color: #fff;
        }
        .btn-azure:hover, .btn-azure:focus {
            background-color: var(--electric-blue-dark);
            border-color: var(--electric-blue-dark);
            color: #fff;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border-color: var(--border-soft);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--electric-blue);
            box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.15);
        }

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

        .icon-input-group { position: relative; }
        .icon-input-group .form-control { padding-right: 2.5rem; }
        .icon-input-suffix {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            pointer-events: none;
        }

        .auth-divider { display: flex; align-items: center; text-align: center; color: #94A3B8; font-size: 0.85rem; }
        .auth-divider::before, .auth-divider::after { content: ""; flex: 1; border-bottom: 1px solid var(--border-soft); }
        .auth-divider span { padding: 0 0.75rem; }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background-color: #fff;
            border: 1px solid var(--border-soft);
            color: var(--navy);
            font-weight: 500;
        }
        .btn-google:hover { background-color: #F8FAFC; color: var(--navy); border-color: var(--border-soft); }
        .btn-google i { color: #4285F4; }

        .role-toggle .btn-outline-azure {
            color: var(--electric-blue);
            border-color: var(--border-soft);
            background-color: #fff;
        }
        .role-toggle .btn-check:checked + .btn-outline-azure {
            background-color: var(--electric-blue);
            border-color: var(--electric-blue);
            color: #fff;
        }

        .auth-card a { color: var(--electric-blue); text-decoration: none; font-weight: 500; }
        .auth-card a:hover { text-decoration: underline; color: var(--electric-blue-dark); }
        .clock-o { width: 0.78em; height: 0.78em; display: inline-block; vertical-align: -0.05em; margin-right: 1px; color: var(--navy); }
    </style>
</head>
<body class="auth-body">

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
