@extends('layouts.app')

@section('title', 'Dashboard · PMMS')
@section('page-title', 'Financial cockpit')

@section('content')
    <div class="welcome-panel">
        <div class="welcome-copy">
            <span class="eyebrow">Cockpit</span>
            <h2>Welcome back, <strong>{{ auth()->user()->name }}</strong></h2>
            <p class="muted">Here's your comprehensive financial cashflow and loan tracker overview for today.</p>
        </div>
        <div class="welcome-actions">
            <a href="{{ route('reports.index') }}" class="btn btn-soft">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z"/></svg>
                <span>Analytics Reports</span>
            </a>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>Add Expense</span>
            </a>
        </div>
    </div>

    <section class="stats-grid">
        <!-- Card 1: Net cash balance -->
        <article class="stat-card balance">
            <div class="stat-header">
                <span>Net Available Funds</span>
                <span class="stat-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6A2.25 2.25 0 0 1 18.75 20H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3"/></svg>
                </span>
            </div>
            <strong>৳{{ number_format($stats['current_balance'], 2) }}</strong>
            <small>Actual cash liquid reserve after loan payouts</small>
        </article>

        <!-- Card 2: Income -->
        <article class="stat-card">
            <div class="stat-header">
                <span>Total Inflow</span>
                <span class="stat-icon inflow">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 4.5l-15 15m0 0h11.25m-11.25 0V8.25"/></svg>
                </span>
            </div>
            <strong>৳{{ number_format($stats['total_income'], 2) }}</strong>
            <div class="stat-sub-grid">
                <div>
                    <span>Received (Paid)</span>
                    <strong>৳{{ number_format($stats['total_income'], 2) }}</strong>
                </div>
                <div>
                    <span>Awaiting (Pending)</span>
                    <strong>৳{{ number_format($stats['pending_income'], 2) }}</strong>
                </div>
            </div>
        </article>

        <!-- Card 3: Expenses -->
        <article class="stat-card">
            <div class="stat-header">
                <span>Total Outflow</span>
                <span class="stat-icon outflow">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"/></svg>
                </span>
            </div>
            <strong>৳{{ number_format($stats['total_expenses'], 2) }}</strong>
            <div class="stat-sub-grid">
                <div>
                    <span>Paid (Outflow)</span>
                    <strong>৳{{ number_format($stats['total_expenses'], 2) }}</strong>
                </div>
                <div>
                    <span>Due (Pending)</span>
                    <strong>৳{{ number_format($stats['pending_expense'], 2) }}</strong>
                </div>
            </div>
        </article>

        <!-- Card 4: Loans -->
        @php($netLoan = $stats['pending_receivables'] - $stats['pending_payables'])
        <article @class(['stat-card', 'loan-positive' => $netLoan >= 0, 'loan-negative' => $netLoan < 0])>
            <div class="stat-header">
                <span>Net Outstanding Loans</span>
                <span class="stat-icon loan">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3-3m0 0 3-3m-3 3h12.75A6.75 6.75 0 0 0 21 14.25 6.75 6.75 0 0 0 14.25 7.5H3"/></svg>
                </span>
            </div>
            <strong>
                {{ $netLoan >= 0 ? '+' : '' }}৳{{ number_format($netLoan, 2) }}
            </strong>
            <div class="stat-sub-grid">
                <div>
                    <span>Lent (Receivable)</span>
                    <strong>৳{{ number_format($stats['pending_receivables'], 2) }}</strong>
                </div>
                <div>
                    <span>Borrowed (Payable)</span>
                    <strong>৳{{ number_format($stats['pending_payables'], 2) }}</strong>
                </div>
            </div>
        </article>
    </section>

    <section class="panel quick-access-panel">
        <div class="category-quick-access">
            <div class="category-group">
                <h4>Income Categories</h4>
                <div class="category-chips">
                    @forelse($categories->get('income', []) as $category)
                        <a href="{{ route('incomes.index', ['category_id' => $category->id]) }}" class="category-chip" style="--chip-color: {{ $category->color }}">
                            {{ $category->name }}
                        </a>
                    @empty
                        <p class="empty-state">No income categories defined.</p>
                    @endforelse
                </div>
            </div>
            <div class="category-group">
                <h4>Expense Categories</h4>
                <div class="category-chips">
                    @forelse($categories->get('expense', []) as $category)
                        <a href="{{ route('expenses.index', ['category_id' => $category->id]) }}" class="category-chip" style="--chip-color: {{ $category->color }}">
                            {{ $category->name }}
                        </a>
                    @empty
                        <p class="empty-state">No expense categories defined.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <section class="two-column">
        <article class="panel">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Monthly Trend</p>
                    <h3>Income vs Expense</h3>
                </div>
                <span class="badge badge-info">Last 6 Months</span>
            </div>
            <div class="chart-wrapper">
                <canvas class="chart-canvas" data-line-chart='@json($monthlyTrend)'></canvas>
            </div>
        </article>

        <article class="panel">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Spend Mix</p>
                    <h3>Category-wise Expenses</h3>
                </div>
            </div>
            @php($maxExpense = max($expenseBreakdown->max('total') ?? 1, 1))
            <div class="progress-list">
                @forelse ($expenseBreakdown as $item)
                    <div class="progress-item">
                        <div class="progress-meta">
                            <strong>{{ $item['label'] }}</strong>
                            <span>৳{{ number_format($item['total'], 2) }}</span>
                        </div>
                        <div class="progress-track">
                            <span class="progress-fill" style="width: {{ ($item['total'] / $maxExpense) * 100 }}%; background: {{ $item['color'] }}"></span>
                        </div>
                    </div>
                @empty
                    <p class="empty-state">Add some paid expenses to see the category chart.</p>
                @endforelse
            </div>
        </article>
    </section>

    <section class="two-column">
        <article class="panel">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Recent Activity</p>
                    <h3>Last 10 Transactions</h3>
                </div>
            </div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Details</th>
                            <th>Person</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTransactions as $item)
                            <tr>
                                <td>
                                    <span @class([
                                        'badge',
                                        'badge-inflow' => $item['type'] === 'Income',
                                        'badge-outflow' => $item['type'] === 'Expense',
                                        'badge-loan' => in_array($item['type'], ['Given', 'Taken']),
                                    ])>
                                        {{ $item['type'] }}
                                    </span>
                                </td>
                                <td><a class="transaction-link" href="{{ $item['route'] }}">{{ $item['title'] }}</a></td>
                                <td>{{ $item['person'] }}</td>
                                <td class="amount-cell">৳{{ number_format($item['amount'], 2) }}</td>
                                <td>{{ optional($item['date'])->format('d M Y') }}</td>
                                <td><span class="status status-{{ $item['status'] }}">{{ ucfirst($item['status']) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">Your recent transactions will appear here.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        <article class="panel">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Upcoming Reminders</p>
                    <h3>Overdue & Upcoming Actions</h3>
                </div>
                <a href="{{ route('reminders.index') }}" class="btn btn-soft">Manage</a>
            </div>
            <div class="reminder-list">
                @forelse ($upcomingReminders as $item)
                    <a href="{{ $item['route'] }}" class="reminder-card">
                        <div class="reminder-info">
                            <strong>{{ $item['title'] }}</strong>
                            <p>{{ $item['note'] ?: 'No extra note added.' }}</p>
                        </div>
                        <div class="reminder-meta">
                            @if (! is_null($item['amount']))
                                <span class="reminder-amount">৳{{ number_format($item['amount'], 2) }}</span>
                            @endif
                            <span @class([
                                'reminder-due-tag',
                                'due-overdue' => $item['days_left'] < 0,
                                'due-today' => $item['days_left'] === 0,
                                'due-upcoming' => $item['days_left'] > 0,
                            ])>
                                {{ $item['days_left'] < 0 ? abs($item['days_left']).'d overdue' : ($item['days_left'] === 0 ? 'Today' : 'in '.$item['days_left'].'d') }}
                            </span>
                        </div>
                    </a>
                @empty
                    <p class="empty-state">No reminders due soon. Nice work staying ahead.</p>
                @endforelse
            </div>
        </article>
    </section>
@endsection

