<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Support\ReminderCenter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReminderController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'type', 'date_from', 'date_to']);

        $reminders = Reminder::query()
            ->ownedBy($request->user())
            ->filter($filters)
            ->orderBy('reminder_date')
            ->paginate(12)
            ->withQueryString();

        return view('reminders.index', [
            'reminders' => $reminders,
            'filters' => $filters,
            'upcoming' => ReminderCenter::upcoming($request->user(), 30)
                ->filter(fn (array $reminder) => $reminder['origin'] === 'auto')
                ->take(8)
                ->values(),
        ]);
    }

    public function create(): View
    {
        return view('reminders.create', [
            'reminder' => new Reminder(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Reminder::create($this->validatedData($request));

        return redirect()
            ->route('reminders.index')
            ->with('success', 'Reminder created successfully.');
    }

    public function show(string $id): never
    {
        abort(404);
    }

    public function edit(Request $request, string $reminder): View
    {
        /** @var \App\Models\Reminder $reminder */
        $reminder = $this->resolveOwnedModel($request, 'reminder', Reminder::class);

        return view('reminders.edit', compact('reminder'));
    }

    public function update(Request $request, string $reminder): RedirectResponse
    {
        /** @var \App\Models\Reminder $reminder */
        $reminder = $this->resolveOwnedModel($request, 'reminder', Reminder::class);

        $reminder->update($this->validatedData($request));

        return redirect()
            ->route('reminders.index')
            ->with('success', 'Reminder updated successfully.');
    }

    public function destroy(Request $request, string $reminder): RedirectResponse
    {
        /** @var \App\Models\Reminder $reminder */
        $reminder = $this->resolveOwnedModel($request, 'reminder', Reminder::class);

        $reminder->delete();

        return redirect()
            ->route('reminders.index')
            ->with('success', 'Reminder deleted successfully.');
    }

    public function markComplete(Request $request, string $reminder): RedirectResponse
    {
        /** @var \App\Models\Reminder $reminder */
        $reminder = $this->resolveOwnedModel($request, 'reminder', Reminder::class);

        $reminder->update(['status' => 'completed']);

        return back()->with('success', 'Reminder marked as completed.');
    }

    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'type' => ['required', Rule::in(array_keys(Reminder::TYPES))],
            'channel' => ['required', Rule::in(array_keys(Reminder::CHANNELS))],
            'reminder_date' => ['required', 'date'],
            'status' => ['required', Rule::in(array_keys(Reminder::STATUSES))],
            'notes' => ['nullable', 'string', 'max:3000'],
        ]);
    }
}
