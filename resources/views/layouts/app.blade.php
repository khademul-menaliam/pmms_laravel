<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PMMS')</title>
    <link rel="stylesheet" href="{{ asset('css/pmms.css') }}">
</head>
<body>
    @php
        $navItems = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'DB'],
            ['label' => 'Income', 'route' => 'incomes.index', 'icon' => 'IN'],
            ['label' => 'Expenses', 'route' => 'expenses.index', 'icon' => 'EX'],
            ['label' => 'Given Money', 'route' => 'given-loans.index', 'icon' => 'GV'],
            ['label' => 'Taken Money', 'route' => 'taken-loans.index', 'icon' => 'TK'],
            ['label' => 'Categories', 'route' => 'categories.index', 'icon' => 'CT'],
            ['label' => 'Reports', 'route' => 'reports.index', 'icon' => 'RP'],
            ['label' => 'Reminders', 'route' => 'reminders.index', 'icon' => 'RM'],
            ['label' => 'Search', 'route' => 'search.index', 'icon' => 'SR'],
            ['label' => 'Backup', 'route' => 'backup.index', 'icon' => 'BK'],
        ];
        $user = auth()->user();
    @endphp

    <div class="app-shell">
        <aside class="sidebar" data-nav-panel>
            <div class="brand">
                <div class="brand-mark">PM</div>
                <div>
                    <strong>Money Manager</strong>
                    <p>Secure personal finance workspace</p>
                </div>
            </div>

            <div class="sidebar-user">
                <strong>{{ $user->name }}</strong>
                <span>{{ $user->email }}</span>
                <small>{{ strtoupper($user->currency) }} account</small>
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
                        <span class="nav-icon">{{ $item['icon'] }}</span>
                        <span>{{ $item['label'] }}</span>
                        @if ($item['route'] === 'reminders.index' && ($pendingAlertsCount ?? 0))
                            <span class="nav-badge">{{ $pendingAlertsCount }}</span>
                        @endif
                    </a>
                @endforeach

                @if ($user->is_superadmin)
                    <a href="{{ route('admin.users.index') }}" @class(['nav-link', 'active' => request()->routeIs('admin.*')])>
                        <span class="nav-icon">AD</span>
                        <span>Admin Users</span>
                    </a>
                @endif
            </nav>

            <div class="sidebar-foot">
                <p>{{ now()->format('d M Y') }}</p>
                <span>Multi-user Laravel + Blade finance tracker</span>
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
                <button class="menu-toggle" type="button" data-nav-toggle>Menu</button>

                <div>
                    <p class="eyebrow">Personal Money Management System</p>
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
