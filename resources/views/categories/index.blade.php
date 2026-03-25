@extends('layouts.app')

@section('title', 'Categories · PMMS')
@section('page-title', 'Categories')

@section('content')
    <section class="section-row">
        <div class="mini-stats">
            <article class="mini-card">
                <span>Income Categories</span>
                <strong>{{ $summary['income'] }}</strong>
            </article>
            <article class="mini-card">
                <span>Expense Categories</span>
                <strong>{{ $summary['expense'] }}</strong>
            </article>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">Add category</a>
    </section>

    <section class="panel">
        <form method="GET" class="filter-grid">
            <label class="field">
                <span>Type</span>
                <select name="type">
                    <option value="">All</option>
                    @foreach (\App\Models\Category::TYPE_OPTIONS as $value => $label)
                        <option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('categories.index') }}" class="btn btn-soft">Reset</a>
            </div>
        </form>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Color</th>
                        <th>Description</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td><span class="badge">{{ ucfirst($category->type) }}</span></td>
                            <td>
                                <span class="color-dot" style="background: {{ $category->color }}"></span>
                                {{ $category->color }}
                            </td>
                            <td>{{ $category->description ?: '—' }}</td>
                            <td class="table-actions">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-soft">Edit</a>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this category?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">No categories found yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $categories->links() }}
    </section>
@endsection
