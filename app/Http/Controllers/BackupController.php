<?php

namespace App\Http\Controllers;

use App\Support\BackupManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use JsonException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function index(): View
    {
        $directory = BackupManager::directoryFor(auth()->user());

        $files = collect(Storage::disk('local')->files($directory))
            ->map(fn (string $path): array => [
                'path' => $path,
                'name' => basename($path),
                'size' => Storage::disk('local')->size($path),
                'modified_at' => Storage::disk('local')->lastModified($path),
            ])
            ->sortByDesc('modified_at')
            ->values();

        return view('backup.index', [
            'files' => $files,
        ]);
    }

    public function export(): BinaryFileResponse
    {
        $path = BackupManager::storeSnapshot(auth()->user());

        return response()->download(storage_path('app/'.$path));
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'backup_file' => ['required', 'file', 'mimes:json', 'max:4096'],
        ]);

        try {
            $payload = json_decode($request->file('backup_file')->get(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return back()->with('error', 'The uploaded file is not a valid PMMS backup.');
        }

        BackupManager::import($request->user(), $payload);

        return back()->with('success', 'Backup imported successfully. Existing data was preserved and new data was appended.');
    }

    public function download(string $file): BinaryFileResponse
    {
        abort_unless(preg_match('/^[A-Za-z0-9\\-\\._]+\\.json$/', $file) === 1, 404);

        $path = BackupManager::directoryFor(auth()->user()).'/'.$file;
        abort_unless(Storage::disk('local')->exists($path), 404);

        return response()->download(storage_path('app/'.$path));
    }
}
