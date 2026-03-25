<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PMMS')</title>
    <link rel="stylesheet" href="{{ asset('css/pmms.css') }}">
</head>
<body class="guest-page">
    <div class="auth-shell">
        <section class="auth-hero">
            <p class="eyebrow">PMMS</p>
            <h1>Keep each user's money records private, clean, and easy to manage.</h1>
            <p>
                A lightweight Laravel finance system with isolated dashboards, quick entry,
                reminders, reports, and a premium interface that still feels fast.
            </p>
            <div class="mini-stats">
                <div class="mini-card">
                    <span class="muted">Secure access</span>
                    <strong>Per-user data</strong>
                </div>
                <div class="mini-card">
                    <span class="muted">Personal workflow</span>
                    <strong>Profile + backup</strong>
                </div>
            </div>
        </section>

        <section class="auth-panel">
            <div class="auth-card">
                @include('partials.flash')
                @yield('content')
            </div>
        </section>
    </div>
</body>
</html>
