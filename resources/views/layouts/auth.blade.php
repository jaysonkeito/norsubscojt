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

        /* ---- Split auth card: sliding welcome panel + form ----
           Login: panel LEFT / form right. Register: panel RIGHT / form left.
           The .pre class parks the panel on the opposite side; a tiny
           script removes it right after load so it slides into place,
           giving the "panel transfers across" feel between pages. */
        .auth-body.has-split .auth-card-wrap { max-width: 1000px; }
        .auth-body.has-split .auth-card-wrap > .alert { max-width: 960px; margin-left: auto; margin-right: auto; }
        .auth-split-card {
            position: relative;
            max-width: 960px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            height: 760px;
        }
        .auth-form-col {
            grid-column: 1;
            padding: 2.75rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
            transition: opacity .5s ease .15s, transform .5s ease .15s;
        }
        .auth-form-col.col-right { grid-column: 2; }
        .auth-split-card.pre .auth-form-col { opacity: 0; transform: translateY(12px); }
        .auth-welcome-panel {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 50%;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 3rem 2.25rem;
            color: #fff;
            background: linear-gradient(135deg, #1E3A8A 0%, var(--electric-blue-dark) 45%, var(--electric-blue) 100%);
            transition: left .65s cubic-bezier(.65, 0, .35, 1);
        }
        .auth-split-card.panel-left .auth-welcome-panel { left: 0; }
        .auth-split-card.pre.panel-left .auth-welcome-panel { left: 50%; }
        .auth-split-card.pre.panel-right .auth-welcome-panel { left: 0; }
        .auth-welcome-panel::before,
        .auth-welcome-panel::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }
        .auth-welcome-panel::before { width: 300px; height: 300px; top: -90px; left: -90px; background: rgba(255,255,255,0.08); }
        .auth-welcome-panel::after { width: 240px; height: 240px; bottom: -80px; right: -70px; background: rgba(255,255,255,0.06); }
        .welcome-inner { position: relative; z-index: 1; max-width: 340px; }
        .welcome-inner h2 { color: #fff; font-weight: 800; font-size: clamp(1.6rem, 2.4vw, 2rem); letter-spacing: -0.02em; }
        .welcome-inner p { color: rgba(255,255,255,0.85); }
        .welcome-inner .welcome-cta-hint {
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255,255,255,0.7);
            margin: 1.5rem 0 0.5rem;
        }
        .btn-welcome-cta {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: #fff;
            color: var(--electric-blue-dark);
            border-radius: 999px;
            padding: .6rem 1.9rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(15,23,42,0.18);
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .btn-welcome-cta:hover { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(15,23,42,0.24); color: var(--navy); }
        .auth-welcome-panel .auth-brand-icon { background: rgba(255,255,255,0.16); margin: 0 auto 1.4rem; }
        /* Brand block on the form side only shows when the panel is hidden (mobile) */
        .form-brand { display: none; }
        @media (max-width: 860px) {
            .auth-welcome-panel { display: none; }
            .auth-split-card { grid-template-columns: 1fr; height: auto; }
            .auth-form-col, .auth-form-col.col-right { grid-column: 1; }
            .form-brand { display: block; }
        }
        @media (prefers-reduced-motion: reduce) {
            .auth-welcome-panel, .auth-form-col { transition: none; }
        }
    </style>
</head>
@php
    // Views opt into the two-panel split layout by defining a
    // 'welcome_panel' section; 'welcome_side' picks which side it lands on.
    $hasWelcomePanel = trim((string) $__env->yieldContent('welcome_panel')) !== '';
    $panelSide = trim((string) $__env->yieldContent('welcome_side')) ?: 'left';
@endphp
<body class="auth-body {{ $hasWelcomePanel ? 'has-split' : '' }}">

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
        @if($hasWelcomePanel)
            <div class="card auth-card auth-split-card panel-{{ $panelSide }} pre" id="authSplitCard">
                <div class="auth-form-col {{ $panelSide === 'left' ? 'col-right' : 'col-left' }}">
                    @yield('content')
                </div>
                <div class="auth-welcome-panel">
                    @yield('welcome_panel')
                </div>
            </div>
        @else
            @yield('content')
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Kick off the panel slide-in one frame after load so the
        // transition actually plays from the parked (.pre) position.
        document.addEventListener('DOMContentLoaded', function () {
            var card = document.getElementById('authSplitCard');
            if (!card) return;
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    card.classList.remove('pre');
                });
            });
        });
    </script>
</body>
</html>
