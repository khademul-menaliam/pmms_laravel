@extends('layouts.app')

@section('title', 'Expenses · PMMS')
@section('page-title', 'Expense management')

@section('content')
    <section class="section-row">
        <div class="mini-stats">
            <article class="mini-card">
                <span>Paid</span>
                <strong>৳{{ number_format($summary['paid'], 2) }}</strong>
            </article>
            <article class="mini-card">
                <span>Pending</span>
                <strong>৳{{ number_format($summary['pending'], 2) }}</strong>
            </article>
            <article class="mini-card">
                <span>Due Items</span>
                <strong>{{ $summary['due'] }}</strong>
            </article>
        </div>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">Add expense</a>
    </section>

    <section class="panel">
        <form method="GET" class="filter-grid">
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
                <span>Status</span>
                <select name="status">
                    <option value="">All</option>
                    @foreach (\App\Models\Expense::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? null) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">
                <span>Date From</span>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
            </label>
            <label class="field">
                <span>Date To</span>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
            </label>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('expenses.index') }}" class="btn btn-soft">Reset</a>
            </div>
        </form>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Paid Via</th>
                        <th>Paid To</th>
                        <th>Dates</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>{{ $expense->category?->name }}</td>
                            <td>৳{{ number_format($expense->amount, 2) }}</td>
                            <td><span class="status status-{{ $expense->status }}">{{ ucfirst($expense->status) }}</span></td>
                            <td>{{ \App\Models\Expense::PAYMENT_OPTIONS[$expense->paid_via] ?? '—' }}</td>
                            <td>{{ $expense->paid_to ?: '—' }}</td>
                            <td>
                                Expense: {{ optional($expense->expense_date)->format('d M Y') ?: '—' }}<br>
                                Due: {{ optional($expense->due_date)->format('d M Y') ?: '—' }}
                            </td>
                            <td class="table-actions">
                                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-soft">Edit</a>
                                @if ($expense->status === 'pending')
                                    <form method="POST" action="{{ route('expenses.mark-paid', $expense) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-primary">Mark paid</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('expenses.destroy', $expense) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this expense?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">No expense records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $expenses->links() }}
    </section>
@endsection
