@extends('layouts.app')

@section('title', 'Reports · PMMS')
@section('page-title', 'Reports & analytics')

@section('content')
    <section class="section-row">
        <div class="hero-copy">
            <p class="eyebrow">Understand the pattern</p>
            <h2>Measure cash flow, category pressure, receivables, and payables from one report screen.</h2>
        </div>
        <div class="hero-actions">
            <a href="{{ route('reports.export', array_merge($filters, ['format' => 'print'])) }}" class="btn btn-soft" target="_blank">Print / PDF</a>
            <a href="{{ route('reports.export', $filters) }}" class="btn btn-primary">Excel-ready CSV</a>
        </div>
    </section>

    <section class="panel">
        <form method="GET" class="filter-grid">
            <label class="field">
                <span>Date From</span>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
            </label>
            <label class="field">
                <span>Date To</span>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
            </label>
            <label class="field">
                <span>Category</span>
                <select name="category_id">
                    <option value="">All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? null) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">
                <span>Person</span>
                <input type="text" name="person" value="{{ $filters['person'] ?? '' }}">
            </label>
            <label class="field">
                <span>Status</span>
                <input type="text" name="status" value="{{ $filters['status'] ?? '' }}" placeholder="paid / pending / returned">
            </label>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Apply</button>
                <a href="{{ route('reports.index') }}" class="btn btn-soft">Reset</a>
            </div>
        </form>
    </section>

    <section class="stats-grid compact">
        <article class="stat-card">
            <span>Income</span>
            <strong>৳{{ number_format($summary['income'], 2) }}</strong>
        </article>
        <article class="stat-card">
            <span>Expense</span>
            <strong>৳{{ number_format($summary['expense'], 2) }}</strong>
        </article>
        <article class="stat-card">
            <span>Receivable</span>
            <strong>৳{{ number_format($summary['receivable'], 2) }}</strong>
        </article>
        <article class="stat-card">
            <span>Payable</span>
            <strong>৳{{ number_format($summary['payable'], 2) }}</strong>
        </article>
        <article class="stat-card balance">
            <span>Net Cash Flow</span>
            <strong>৳{{ number_format($summary['net_cash_flow'], 2) }}</strong>
        </article>
    </section>

    <section class="two-column">
        <article class="panel">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Cash flow</p>
                    <h3>Monthly income vs expense</h3>
                </div>
            </div>
            <canvas class="chart-canvas" data-line-chart='@json($cashFlow)'></canvas>
        </article>

        <article class="panel">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Expense focus</p>
                    <h3>Category wise pressure points</h3>
                </div>
            </div>
            @php($maxExpense = max($expenseByCategory->max('total') ?? 1, 1))
            <div class="progress-list">
                @forelse ($expenseByCategory as $item)
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
                    <p class="empty-state">No paid expenses available for this breakdown.</p>
                @endforelse
            </div>
        </article>
    </section>

    <section class="two-column">
        <article class="panel">
            <div class="section-head"><h3>Latest income rows</h3></div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>Source</th><th>From</th><th>Status</th><th>Amount</th></tr></thead>
                    <tbody>
                        @forelse ($incomes->take(8) as $income)
                            <tr>
                                <td>{{ $income->category?->name }}</td>
                                <td>{{ $income->received_from ?: '—' }}</td>
                                <td><span class="status status-{{ $income->status }}">{{ ucfirst($income->status) }}</span></td>
                                <td>৳{{ number_format($income->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty-state">No income data for these filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        <article class="panel">
            <div class="section-head"><h3>Latest expense rows</h3></div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>Category</th><th>Paid To</th><th>Status</th><th>Amount</th></tr></thead>
                    <tbody>
                        @forelse ($expenses->take(8) as $expense)
                            <tr>
                                <td>{{ $expense->category?->name }}</td>
                                <td>{{ $expense->paid_to ?: '—' }}</td>
                                <td><span class="status status-{{ $expense->status }}">{{ ucfirst($expense->status) }}</span></td>
                                <td>৳{{ number_format($expense->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty-state">No expense data for these filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>

    <section class="panel">
        <div class="section-head"><h3>Given vs taken position</h3></div>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Type</th><th>Person</th><th>Total</th><th>Settled</th><th>Outstanding</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($givenLoans->take(5) as $loan)
                        <tr>
                            <td><span class="badge">Given</span></td>
                            <td>{{ $loan->person_name }}</td>
                            <td>৳{{ number_format($loan->amount, 2) }}</td>
                            <td>৳{{ number_format($loan->returned_amount, 2) }}</td>
                            <td>৳{{ number_format($loan->outstanding_amount, 2) }}</td>
                            <td><span class="status status-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span></td>
                        </tr>
                    @empty
                    @endforelse
                    @forelse ($takenLoans->take(5) as $loan)
                        <tr>
                            <td><span class="badge">Taken</span></td>
                            <td>{{ $loan->person_name }}</td>
                            <td>৳{{ number_format($loan->amount, 2) }}</td>
                            <td>৳{{ number_format($loan->paid_amount, 2) }}</td>
                            <td>৳{{ number_format($loan->outstanding_amount, 2) }}</td>
                            <td><span class="status status-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-state">No loan data for these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
