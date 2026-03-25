@extends('layouts.app')

@section('title', 'Given Money · PMMS')
@section('page-title', 'Given money')

@section('content')
    <section class="section-row">
        <div class="mini-stats">
            <article class="mini-card">
                <span>Total Given</span>
                <strong>৳{{ number_format($summary['given'], 2) }}</strong>
            </article>
            <article class="mini-card">
                <span>Returned</span>
                <strong>৳{{ number_format($summary['returned'], 2) }}</strong>
            </article>
            <article class="mini-card">
                <span>Outstanding</span>
                <strong>৳{{ number_format($summary['outstanding'], 2) }}</strong>
            </article>
        </div>
        <a href="{{ route('given-loans.create') }}" class="btn btn-primary">Add given money</a>
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
                    @foreach (\App\Models\GivenLoan::STATUSES as $value => $label)
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
                <a href="{{ route('given-loans.index') }}" class="btn btn-soft">Reset</a>
            </div>
        </form>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Person</th>
                        <th>Amount</th>
                        <th>Returned</th>
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
                            <td>৳{{ number_format($loan->returned_amount, 2) }}</td>
                            <td>৳{{ number_format($loan->outstanding_amount, 2) }}</td>
                            <td>
                                Given: {{ optional($loan->given_date)->format('d M Y') }}<br>
                                Return: {{ optional($loan->expected_return_date)->format('d M Y') ?: '—' }}
                            </td>
                            <td><span class="status status-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span></td>
                            <td class="table-actions">
                                <a href="{{ route('given-loans.edit', $loan) }}" class="btn btn-soft">Edit</a>
                                @if ($loan->status !== 'returned')
                                    <form method="POST" action="{{ route('given-loans.mark-returned', $loan) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-primary">Mark returned</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('given-loans.destroy', $loan) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this record?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">No given money records yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $loans->links() }}
    </section>
@endsection
