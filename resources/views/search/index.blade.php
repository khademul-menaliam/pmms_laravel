@extends('layouts.app')

@section('title', 'Search · PMMS')
@section('page-title', 'Search & filter')

@section('content')
    <section class="panel">
        <form method="GET" class="filter-grid search-grid">
            <label class="field"><span>Date From</span><input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"></label>
            <label class="field"><span>Date To</span><input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"></label>
            <label class="field"><span>Amount Min</span><input type="number" step="0.01" min="0" name="amount_min" value="{{ $filters['amount_min'] ?? '' }}"></label>
            <label class="field"><span>Amount Max</span><input type="number" step="0.01" min="0" name="amount_max" value="{{ $filters['amount_max'] ?? '' }}"></label>
            <label class="field">
                <span>Category</span>
                <select name="category_id">
                    <option value="">All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? null) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field"><span>Person</span><input type="text" name="person" value="{{ $filters['person'] ?? '' }}" placeholder="Name or company"></label>
            <label class="field"><span>Status</span><input type="text" name="status" value="{{ $filters['status'] ?? '' }}" placeholder="paid / pending / returned"></label>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('search.index') }}" class="btn btn-soft">Reset</a>
            </div>
        </form>
    </section>

    <section class="mini-stats">
        <article class="mini-card"><span>Income Results</span><strong>{{ $counts['incomes'] }}</strong></article>
        <article class="mini-card"><span>Expense Results</span><strong>{{ $counts['expenses'] }}</strong></article>
        <article class="mini-card"><span>Given Results</span><strong>{{ $counts['given'] }}</strong></article>
        <article class="mini-card"><span>Taken Results</span><strong>{{ $counts['taken'] }}</strong></article>
    </section>

    @unless ($hasFilters)
        <p class="muted">Showing recent records. Add filters to narrow down exact transactions.</p>
    @endunless

    <section class="two-column">
        <article class="panel">
            <div class="section-head"><h3>Income</h3></div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>Source</th><th>From</th><th>Amount</th></tr></thead>
                    <tbody>
                        @forelse ($results['incomes'] as $income)
                            <tr><td>{{ $income->category?->name }}</td><td>{{ $income->received_from ?: '—' }}</td><td>৳{{ number_format($income->amount, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="empty-state">No income matches.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        <article class="panel">
            <div class="section-head"><h3>Expenses</h3></div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>Category</th><th>Paid To</th><th>Amount</th></tr></thead>
                    <tbody>
                        @forelse ($results['expenses'] as $expense)
                            <tr><td>{{ $expense->category?->name }}</td><td>{{ $expense->paid_to ?: '—' }}</td><td>৳{{ number_format($expense->amount, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="empty-state">No expense matches.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>

    <section class="two-column">
        <article class="panel">
            <div class="section-head"><h3>Given Money</h3></div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>Person</th><th>Amount</th><th>Outstanding</th></tr></thead>
                    <tbody>
                        @forelse ($results['given'] as $loan)
                            <tr><td>{{ $loan->person_name }}</td><td>৳{{ number_format($loan->amount, 2) }}</td><td>৳{{ number_format($loan->outstanding_amount, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="empty-state">No given-money matches.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        <article class="panel">
            <div class="section-head"><h3>Taken Money</h3></div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>Person</th><th>Amount</th><th>Outstanding</th></tr></thead>
                    <tbody>
                        @forelse ($results['taken'] as $loan)
                            <tr><td>{{ $loan->person_name }}</td><td>৳{{ number_format($loan->amount, 2) }}</td><td>৳{{ number_format($loan->outstanding_amount, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="empty-state">No taken-money matches.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
@endsection
