@extends('layouts.app')

@section('title', 'Dashboard · PMMS')
@section('page-title', 'Financial cockpit')

@section('content')
    <section class="hero-card">
        <div>
            <p class="eyebrow">Money at a glance</p>
            <h2>See cash flow, loans, and reminders in one lightweight Laravel dashboard.</h2>
            <p class="muted">Track income, expenses, receivables, payables, and upcoming due dates without leaving the page.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('reports.index') }}" class="btn btn-primary">Open reports</a>
            <a href="{{ route('reminders.create') }}" class="btn btn-soft">Add reminder</a>
        </div>
    </section>

    <section class="stats-grid">
        <article class="stat-card balance">
            <span>Current Balance</span>
            <strong>৳{{ number_format($stats['current_balance'], 2) }}</strong>
            <small>Real cash after loans in/out</small>
        </article>
        <article class="stat-card">
            <span>Total Income</span>
            <strong>৳{{ number_format($stats['total_income'], 2) }}</strong>
            <small>Paid income only</small>
        </article>
        <article class="stat-card">
            <span>Total Expenses</span>
            <strong>৳{{ number_format($stats['total_expenses'], 2) }}</strong>
            <small>Paid expenses only</small>
        </article>
        <article class="stat-card">
            <span>Total Given</span>
            <strong>৳{{ number_format($stats['total_given'], 2) }}</strong>
            <small>Money lent to others</small>
        </article>
        <article class="stat-card">
            <span>Total Taken</span>
            <strong>৳{{ number_format($stats['total_taken'], 2) }}</strong>
            <small>Borrowed money</small>
        </article>
        <article class="stat-card">
            <span>Pending Receivables</span>
            <strong>৳{{ number_format($stats['pending_receivables'], 2) }}</strong>
            <small>Expected collections</small>
        </article>
        <article class="stat-card">
            <span>Pending Payables</span>
            <strong>৳{{ number_format($stats['pending_payables'], 2) }}</strong>
            <small>Upcoming returns</small>
        </article>
        <article class="stat-card">
            <span>Pending Income / Expense</span>
            <strong>৳{{ number_format($stats['pending_income'], 2) }} / ৳{{ number_format($stats['pending_expense'], 2) }}</strong>
            <small>Expected inflow vs due outflow</small>
        </article>
    </section>

    <section class="panel">
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
                    <p class="eyebrow">Monthly trend</p>
                    <h3>Income vs expense</h3>
                </div>
                <span class="badge badge-info">Last 6 months</span>
            </div>
            <canvas class="chart-canvas" data-line-chart='@json($monthlyTrend)'></canvas>
        </article>

        <article class="panel">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Spend mix</p>
                    <h3>Category wise expense chart</h3>
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
                    <p class="eyebrow">Recent activity</p>
                    <h3>Last 10 transactions</h3>
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
                                <td><span class="badge">{{ $item['type'] }}</span></td>
                                <td><a href="{{ $item['route'] }}">{{ $item['title'] }}</a></td>
                                <td>{{ $item['person'] }}</td>
                                <td>৳{{ number_format($item['amount'], 2) }}</td>
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
                    <p class="eyebrow">Upcoming reminders</p>
                    <h3>Collect, pay, and follow up on time</h3>
                </div>
                <a href="{{ route('reminders.index') }}" class="btn btn-soft">Manage reminders</a>
            </div>
            <div class="reminder-list">
                @forelse ($upcomingReminders as $item)
                    <a href="{{ $item['route'] }}" class="reminder-card">
                        <div>
                            <strong>{{ $item['title'] }}</strong>
                            <p>{{ $item['note'] ?: 'No extra note added.' }}</p>
                            <small class="muted">Open source record</small>
                        </div>
                        <div class="reminder-meta">
                            @if (! is_null($item['amount']))
                                <span>৳{{ number_format($item['amount'], 2) }}</span>
                            @endif
                            <small>
                                {{ $item['days_left'] < 0 ? abs($item['days_left']).' day(s) overdue' : ($item['days_left'] === 0 ? 'Due today' : 'Due in '.$item['days_left'].' day(s)') }}
                            </small>
                        </div>
                    </a>
                @empty
                    <p class="empty-state">No reminders due soon. Nice work staying ahead.</p>
                @endforelse
            </div>
        </article>
    </section>
@endsection
