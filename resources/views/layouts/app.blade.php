<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PMMS')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pmms.css') }}">
</head>
<body>
    @php
        $navItems = [
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => '<svg class="nav-icon-svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>'
            ],
            [
                'label' => 'Income',
                'route' => 'incomes.index',
                'icon' => '<svg class="nav-icon-svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 5L5 19M5 19h10M5 19V9"/></svg>'
            ],
            [
                'label' => 'Expenses',
                'route' => 'expenses.index',
                'icon' => '<svg class="nav-icon-svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 19L19 5M19 5H9m10 0v10"/></svg>'
            ],
            [
                'label' => 'Given Money',
                'route' => 'given-loans.index',
                'icon' => '<svg class="nav-icon-svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>'
            ],
            [
                'label' => 'Taken Money',
                'route' => 'taken-loans.index',
                'icon' => '<svg class="nav-icon-svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            ],
            [
                'label' => 'Categories',
                'route' => 'categories.index',
                'icon' => '<svg class="nav-icon-svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'
            ],
            [
                'label' => 'Reports',
                'route' => 'reports.index',
                'icon' => '<svg class="nav-icon-svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 00-2 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>'
            ],
            [
                'label' => 'Reminders',
                'route' => 'reminders.index',
                'icon' => '<svg class="nav-icon-svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>'
            ],
            [
                'label' => 'Search',
                'route' => 'search.index',
                'icon' => '<svg class="nav-icon-svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'
            ],
            [
                'label' => 'Backup',
                'route' => 'backup.index',
                'icon' => '<svg class="nav-icon-svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>'
            ],
        ];
        $user = auth()->user();
    @endphp

    <div class="app-shell">
        <aside class="sidebar" data-nav-panel>
            <div class="brand">
                <div class="brand-mark">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-1.971-.659-1.171-.88-1.171-2.303 0-3.182 1.171-.879 3.07-.879 4.242 0 .28.21.503.48.66.777m-3.22-.777a11.05 11.05 0 0 0-3.478 2.404M12 3v1m0 16v1m9-9e-10c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10Z"/></svg>
                </div>
                <div>
                    <strong>PMMS Workspace</strong>
                    <p>Personal Finance</p>
                </div>
            </div>

            <div class="sidebar-user">
                <div class="user-avatar">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="user-info">
                    <strong>{{ $user->name }}</strong>
                    <span>{{ $user->email }}</span>
                    <small>{{ strtoupper($user->currency) }} Account</small>
                </div>
            </div>

            <nav class="sidebar-nav">
                @foreach ($navItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        @class([
                            'nav-link',
                            'active' => request()->routeIs($item['route']) || request()->routeIs(\Illuminate\Support\Str::beforeLast($item['route'], '.').'.*'),
                        ])
                    >
                        <span class="nav-icon">{!! $item['icon'] !!}</span>
                        <span>{{ $item['label'] }}</span>
                        @if ($item['route'] === 'reminders.index' && ($pendingAlertsCount ?? 0))
                            <span class="nav-badge">{{ $pendingAlertsCount }}</span>
                        @endif
                    </a>
                @endforeach

                @if ($user->is_superadmin)
                    <a href="{{ route('admin.users.index') }}" @class(['nav-link', 'active' => request()->routeIs('admin.*')])>
                        <span class="nav-icon">
                            <svg class="nav-icon-svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </span>
                        <span>Admin Users</span>
                    </a>
                @endif
            </nav>

            <div class="sidebar-foot">
                <p>{{ now()->format('d M Y') }}</p>
                <span>PMMS Finance workspace</span>
            </div>
        </aside>

        <div class="main-shell">
            @if (session()->has('impersonated_user_id'))
                <div class="impersonation-banner">
                    <p>Viewing as <strong>{{ auth()->user()->name }}</strong>. All actions are restricted.</p>
                    <form method="POST" action="{{ route('admin.stop-impersonating') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">Exit View As</button>
                    </form>
                </div>
            @endif

            <header class="topbar">
                <button class="menu-toggle" type="button" data-nav-toggle>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>

                <div class="topbar-title">
                    <p class="eyebrow">Finance Cockpit</p>
                    <h1>@yield('page-title', 'Control your financial story')</h1>
                </div>

                <div class="topbar-actions">
                    <a href="{{ route('incomes.index') }}" class="btn btn-soft">Incomes</a>
                    <a href="{{ route('expenses.index') }}" class="btn btn-primary">Expenses</a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-soft">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline-form">
                        @csrf
                        <button type="submit" class="btn btn-danger">Log Out</button>
                    </form>
                </div>
            </header>

            <main class="content">
                @include('partials.flash')
                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/pmms.js') }}" defer></script>
</body>
</html>
