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
            max-width: 460px;
        }
        .auth-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 16px;
            box-shadow: 0 20px 45px rgba(3, 25, 51, 0.35);
            border: none;
        }
        .auth-card.wide { max-width: 620px; }
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
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
