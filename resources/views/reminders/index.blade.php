@extends('layouts.app')

@section('title', 'Reminders · PMMS')
@section('page-title', 'Reminder center')

@section('content')
    <section class="section-row">
        <div class="hero-copy">
            <p class="eyebrow">Automatic and manual reminders</p>
            <h2>Stay ahead of expected income, due expenses, collections, and repayments.</h2>
        </div>
        <a href="{{ route('reminders.create') }}" class="btn btn-primary">Add reminder</a>
    </section>

    <section class="panel">
        <div class="section-head">
            <div>
                <p class="eyebrow">Auto reminders</p>
                <h3>Due soon from your records</h3>
            </div>
        </div>
        <div class="reminder-list">
            @forelse ($upcoming as $item)
                <a href="{{ $item['route'] }}" class="reminder-card">
                    <div>
                        <strong>{{ $item['title'] }}</strong>
                        <p>{{ $item['note'] ?: 'No details added.' }}</p>
                        <small class="muted">Open source record</small>
                    </div>
                    <div class="reminder-meta">
                        @if (! is_null($item['amount']))
                            <span>৳{{ number_format($item['amount'], 2) }}</span>
                        @endif
                        <small>{{ optional($item['date'])->format('d M Y') }}</small>
                    </div>
                </a>
            @empty
                <p class="empty-state">No auto reminders due soon.</p>
            @endforelse
        </div>
    </section>

    <section class="panel">
        <form method="GET" class="filter-grid">
            <label class="field">
                <span>Status</span>
                <select name="status">
                    <option value="">All</option>
                    @foreach (\App\Models\Reminder::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? null) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">
                <span>Type</span>
                <select name="type">
                    <option value="">All</option>
                    @foreach (\App\Models\Reminder::TYPES as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['type'] ?? null) === $value)>{{ $label }}</option>
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
                <a href="{{ route('reminders.index') }}" class="btn btn-soft">Reset</a>
            </div>
        </form>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Channel</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reminders as $reminder)
                        <tr>
                            <td>{{ $reminder->title }}</td>
                            <td>{{ \App\Models\Reminder::TYPES[$reminder->type] ?? ucfirst($reminder->type) }}</td>
                            <td>{{ \App\Models\Reminder::CHANNELS[$reminder->channel] ?? ucfirst($reminder->channel) }}</td>
                            <td>{{ optional($reminder->reminder_date)->format('d M Y') }}</td>
                            <td><span class="status status-{{ $reminder->status }}">{{ ucfirst($reminder->status) }}</span></td>
                            <td class="table-actions">
                                <a href="{{ route('reminders.edit', $reminder) }}" class="btn btn-soft">Edit</a>
                                @if ($reminder->status !== 'completed')
                                    <form method="POST" action="{{ route('reminders.mark-complete', $reminder) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-primary">Complete</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('reminders.destroy', $reminder) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this reminder?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">No reminders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $reminders->links() }}
    </section>
@endsection
