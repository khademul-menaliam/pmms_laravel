@extends('layouts.app')

@section('title', 'Taken Money · PMMS')
@section('page-title', 'Taken money')

@section('content')
    <section class="section-row">
        <div class="mini-stats">
            <article class="mini-card">
                <span>Total Borrowed</span>
                <strong>৳{{ number_format($summary['borrowed'], 2) }}</strong>
            </article>
            <article class="mini-card">
                <span>Paid Back</span>
                <strong>৳{{ number_format($summary['paid'], 2) }}</strong>
            </article>
            <article class="mini-card">
                <span>Outstanding</span>
                <strong>৳{{ number_format($summary['outstanding'], 2) }}</strong>
            </article>
        </div>
        <a href="{{ route('taken-loans.create') }}" class="btn btn-primary">Add taken money</a>
    </section>

    <section class="panel">
        <form method="GET" class="filter-grid">
            <label class="field">
                <span>Person</span>
                <input type="text" name="person" value="{{ $filters['person'] ?? '' }}">
            </label>
            <label class="field">
                <span>Status</span>
                <select name="status">
                    <option value="">All</option>
                    @foreach (\App\Models\TakenLoan::STATUSES as $value => $label)
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
                <a href="{{ route('taken-loans.index') }}" class="btn btn-soft">Reset</a>
            </div>
        </form>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Person</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Outstanding</th>
                        <th>Dates</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        <tr>
                            <td>{{ $loan->person_name }}</td>
                            <td>৳{{ number_format($loan->amount, 2) }}</td>
                            <td>৳{{ number_format($loan->paid_amount, 2) }}</td>
                            <td>৳{{ number_format($loan->outstanding_amount, 2) }}</td>
                            <td>
                                Borrowed: {{ optional($loan->borrow_date)->format('d M Y') }}<br>
                                Return: {{ optional($loan->return_date)->format('d M Y') ?: '—' }}
                            </td>
                            <td><span class="status status-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span></td>
                            <td class="table-actions">
                                <a href="{{ route('taken-loans.edit', $loan) }}" class="btn btn-soft">Edit</a>
                                @if ($loan->status !== 'paid')
                                    <form method="POST" action="{{ route('taken-loans.mark-paid', $loan) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-primary">Mark paid</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('taken-loans.destroy', $loan) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this record?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">No taken money records yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $loans->links() }}
    </section>
@endsection
