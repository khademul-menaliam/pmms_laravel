<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Income;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class IncomeController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $filters = $request->only(['category_id', 'status', 'date_from', 'date_to']);

        $query = Income::query()
            ->ownedBy($user)
            ->with('category')
            ->filter($filters)
            ->orderByDesc('expected_date')
            ->orderByDesc('created_at');

        $incomes = (clone $query)
            ->paginate(12)
            ->withQueryString();

        return view('incomes.index', [
            'incomes' => $incomes,
            'categories' => Category::query()->ownedBy($user)->income()->orderBy('name')->get(),
            'filters' => $filters,
            'summary' => [
                'paid' => (clone $query)->where('status', 'paid')->sum('amount'),
                'pending' => (clone $query)->where('status', 'pending')->sum('amount'),
                'recurring' => (clone $query)->where('is_recurring', true)->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('incomes.create', [
            'income' => new Income(),
            'categories' => Category::query()->ownedBy(auth()->user())->income()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['is_recurring'] = $request->boolean('is_recurring');

        if (! $data['is_recurring']) {
            $data['recurrence_cycle'] = null;
        }

        if (($data['status'] ?? 'pending') === 'paid' && empty($data['received_date'])) {
            $data['received_date'] = $data['expected_date'] ?? now()->toDateString();
        }

        if (($data['status'] ?? 'pending') === 'pending') {
            $data['received_date'] = null;
        }

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('attachments/incomes', 'public');
        }

        Income::create($data);

        return redirect()
            ->route('incomes.index')
            ->with('success', 'Income added successfully.');
    }

    public function show(string $id): never
    {
        abort(404);
    }

    public function edit(Request $request, string $income): View
    {
        /** @var \App\Models\Income $income */
        $income = $this->resolveOwnedModel($request, 'income', Income::class);

        return view('incomes.edit', [
            'income' => $income,
            'categories' => Category::query()->ownedBy(auth()->user())->income()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, string $income): RedirectResponse
    {
        /** @var \App\Models\Income $income */
        $income = $this->resolveOwnedModel($request, 'income', Income::class);

        $data = $this->validatedData($request);
        $data['is_recurring'] = $request->boolean('is_recurring');

        if (! $data['is_recurring']) {
            $data['recurrence_cycle'] = null;
        }

        if (($data['status'] ?? 'pending') === 'paid' && empty($data['received_date'])) {
            $data['received_date'] = $income->received_date?->toDateString() ?? now()->toDateString();
        }

        if (($data['status'] ?? 'pending') === 'pending') {
            $data['received_date'] = null;
        }

        if ($request->boolean('remove_attachment') && $income->attachment_path) {
            Storage::disk('public')->delete($income->attachment_path);
            $data['attachment_path'] = null;
        }

        if ($request->hasFile('attachment')) {
            if ($income->attachment_path) {
                Storage::disk('public')->delete($income->attachment_path);
            }

            $data['attachment_path'] = $request->file('attachment')->store('attachments/incomes', 'public');
        }

        $income->update($data);

        return redirect()
            ->route('incomes.index')
            ->with('success', 'Income updated successfully.');
    }

    public function destroy(Request $request, string $income): RedirectResponse
    {
        /** @var \App\Models\Income $income */
        $income = $this->resolveOwnedModel($request, 'income', Income::class);

        if ($income->attachment_path) {
            Storage::disk('public')->delete($income->attachment_path);
        }

        $income->delete();

        return redirect()
            ->route('incomes.index')
            ->with('success', 'Income deleted successfully.');
    }

    public function markPaid(Request $request, string $income): RedirectResponse
    {
        /** @var \App\Models\Income $income */
        $income = $this->resolveOwnedModel($request, 'income', Income::class);

        $income->update([
            'status' => 'paid',
            'received_date' => $income->received_date?->toDateString() ?? now()->toDateString(),
        ]);

        return back()->with('success', 'Income marked as paid.');
    }

    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(fn ($query) => $query
                    ->where('type', 'income')
                    ->where('user_id', $request->user()->id)),
            ],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'status' => ['required', Rule::in(array_keys(Income::STATUSES))],
            'received_by' => ['nullable', 'string', 'max:50'],
            'received_from' => ['nullable', 'string', 'max:120'],
            'expected_date' => ['nullable', 'date'],
            'received_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:4000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'remove_attachment' => ['nullable', 'boolean'],
            'recurrence_cycle' => ['nullable', Rule::in(array_keys(Income::RECURRENCE_OPTIONS))],
        ]);
    }
}
