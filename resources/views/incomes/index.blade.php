@extends('layouts.app')

@section('title', 'Income · PMMS')
@section('page-title', 'Income management')

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
                <span>Recurring</span>
                <strong>{{ $summary['recurring'] }}</strong>
            </article>
        </div>
        <a href="{{ route('incomes.create') }}" class="btn btn-primary">Add income</a>
    </section>

    <section class="panel">
        <form method="GET" class="filter-grid">
            <label class="field">
                <span>Source</span>
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
                    @foreach (\App\Models\Income::STATUSES as $value => $label)
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
                <a href="{{ route('incomes.index') }}" class="btn btn-soft">Reset</a>
            </div>
        </form>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Source</th>
                        <th>Amount</th>
                        <th>Reference</th>
                        <th>Status</th>
                        <th>Received By</th>
                        <th>From</th>
                        <th>Dates</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($incomes as $income)
                        <tr>
                            <td>{{ $income->category?->name }}</td>
                            <td>৳{{ number_format($income->amount, 2) }}</td>
                            <td>{{ $income->reference ?: '—' }}</td>
                            <td><span class="status status-{{ $income->status }}">{{ ucfirst($income->status) }}</span></td>
                            <td>{{ \App\Models\Income::RECEIVED_BY_OPTIONS[$income->received_by] ?? '—' }}</td>
                            <td>{{ $income->received_from ?: '—' }}</td>
                            <td>
                                Expected: {{ optional($income->expected_date)->format('d M Y') ?: '—' }}<br>
                                Received: {{ optional($income->received_date)->format('d M Y') ?: '—' }}
                            </td>
                            <td class="table-actions">
                                <a href="{{ route('incomes.edit', $income) }}" class="btn btn-soft">Edit</a>
                                @if ($income->status === 'pending')
                                    <form method="POST" action="{{ route('incomes.mark-paid', $income) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-primary">Mark paid</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('incomes.destroy', $income) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this income?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">No income records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $incomes->links() }}
    </section>
@endsection
