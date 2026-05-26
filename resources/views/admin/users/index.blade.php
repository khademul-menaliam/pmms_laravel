@extends('layouts.app')

@section('title', 'User Management · PMMS Admin')
@section('page-title', 'User management & activity')

@section('content')
    <section class="panel">
        <div class="section-head">
            <div>
                <p class="eyebrow">Overview</p>
                <h3>Manage all registered users</h3>
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Currency</th>
                        <th>Resources</th>
                        <th>Last Activity</th>
                        <th>Last IP</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong><br>
                                <small class="muted">{{ $user->email }}</small>
                            </td>
                            <td><span class="badge">{{ strtoupper($user->currency) }}</span></td>
                            <td>
                                <small>Incomes: {{ $user->incomes_count }}</small><br>
                                <small>Expenses: {{ $user->expenses_count }}</small>
                            </td>
                            <td>{{ $user->last_login_at?->diffForHumans() ?: 'Never' }}</td>
                            <td><code>{{ $user->last_login_ip ?: '—' }}</code></td>
                            <td>
                                <span class="status status-{{ $user->is_blocked ? 'pending' : 'paid' }}">
                                    {{ $user->is_blocked ? 'Blocked' : 'Active' }}
                                </span>
                            </td>
                            <td class="table-actions">
                                <form method="POST" action="{{ route('admin.users.impersonate', $user) }}" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn btn-soft">View As</button>
                                </form>

                                @if ($user->is_blocked)
                                    <form method="POST" action="{{ route('admin.users.unblock', $user) }}" class="inline-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-primary">Unblock</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.block', $user) }}" class="inline-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger">Block</button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Permanently delete this user and all their data?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">No other users registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </section>
@endsection
