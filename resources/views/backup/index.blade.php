@extends('layouts.app')

@section('title', 'Backup · PMMS')
@section('page-title', 'Backup center')

@section('content')
    <section class="two-column">
        <article class="panel">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Full data export</p>
                    <h3>Create a JSON backup</h3>
                </div>
            </div>
            <p class="muted">Exports categories, income, expenses, loans, and reminders into one lightweight backup file.</p>
            <form method="POST" action="{{ route('backup.export') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Export full backup</button>
            </form>
        </article>

        <article class="panel">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Import</p>
                    <h3>Restore or merge backup data</h3>
                </div>
            </div>
            <form method="POST" action="{{ route('backup.import') }}" enctype="multipart/form-data" class="form-grid">
                @csrf
                <label class="field field-full">
                    <span>Backup file</span>
                    <input type="file" name="backup_file" accept=".json" required>
                </label>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Import backup</button>
                </div>
            </form>
            <p class="muted">Import appends records. Use a clean database if you want a full restore.</p>
        </article>
    </section>

    <section class="panel">
        <div class="section-head">
            <div>
                <p class="eyebrow">Stored snapshots</p>
                <h3>Recent local backup files</h3>
            </div>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>File</th><th>Size</th><th>Last Modified</th><th></th></tr></thead>
                <tbody>
                    @forelse ($files as $file)
                        <tr>
                            <td>{{ $file['name'] }}</td>
                            <td>{{ number_format($file['size'] / 1024, 1) }} KB</td>
                            <td>{{ \Illuminate\Support\Carbon::createFromTimestamp($file['modified_at'])->format('d M Y h:i A') }}</td>
                            <td><a href="{{ route('backup.download', $file['name']) }}" class="btn btn-soft">Download</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty-state">No local backup files yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <div class="section-head">
            <div>
                <p class="eyebrow">Auto backup</p>
                <h3>Scheduled command</h3>
            </div>
        </div>
        <p class="muted">A daily backup command is registered as <code>php artisan pmms:backup</code>. Run Laravel scheduler via <code>php artisan schedule:work</code> or your server scheduler to automate it.</p>
    </section>
@endsection
