<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $filters = $request->only(['category_id', 'status', 'date_from', 'date_to']);

        $query = Expense::query()
            ->ownedBy($user)
            ->with('category')
            ->filter($filters)
            ->orderByDesc('expense_date')
            ->orderByDesc('created_at');

        $expenses = (clone $query)
            ->paginate(12)
            ->withQueryString();

        return view('expenses.index', [
            'expenses' => $expenses,
            'categories' => Category::query()->ownedBy($user)->expense()->orderBy('name')->get(),
            'filters' => $filters,
            'summary' => [
                'paid' => (clone $query)->where('status', 'paid')->sum('amount'),
                'pending' => (clone $query)->where('status', 'pending')->sum('amount'),
                'due' => (clone $query)->where('status', 'pending')->whereNotNull('due_date')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('expenses.create', [
            'expense' => new Expense(),
            'categories' => Category::query()->ownedBy(auth()->user())->expense()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('attachments/expenses', 'public');
        }

        Expense::create($data);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense added successfully.');
    }

    public function show(string $id): never
    {
        abort(404);
    }

    public function edit(Request $request, string $expense): View
    {
        /** @var \App\Models\Expense $expense */
        $expense = $this->resolveOwnedModel($request, 'expense', Expense::class);

        return view('expenses.edit', [
            'expense' => $expense,
            'categories' => Category::query()->ownedBy(auth()->user())->expense()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, string $expense): RedirectResponse
    {
        /** @var \App\Models\Expense $expense */
        $expense = $this->resolveOwnedModel($request, 'expense', Expense::class);

        $data = $this->validatedData($request);

        if ($request->boolean('remove_attachment') && $expense->attachment_path) {
            Storage::disk('public')->delete($expense->attachment_path);
            $data['attachment_path'] = null;
        }

        if ($request->hasFile('attachment')) {
            if ($expense->attachment_path) {
                Storage::disk('public')->delete($expense->attachment_path);
            }

            $data['attachment_path'] = $request->file('attachment')->store('attachments/expenses', 'public');
        }

        $expense->update($data);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Request $request, string $expense): RedirectResponse
    {
        /** @var \App\Models\Expense $expense */
        $expense = $this->resolveOwnedModel($request, 'expense', Expense::class);

        if ($expense->attachment_path) {
            Storage::disk('public')->delete($expense->attachment_path);
        }

        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    public function markPaid(Request $request, string $expense): RedirectResponse
    {
        /** @var \App\Models\Expense $expense */
        $expense = $this->resolveOwnedModel($request, 'expense', Expense::class);

        $expense->update([
            'status' => 'paid',
            'expense_date' => $expense->expense_date?->toDateString() ?? now()->toDateString(),
        ]);

        return back()->with('success', 'Expense marked as paid.');
    }

    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(fn ($query) => $query
                    ->where('type', 'expense')
                    ->where('user_id', $request->user()->id)),
            ],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'status' => ['required', Rule::in(array_keys(Expense::STATUSES))],
            'paid_via' => ['nullable', 'string', 'max:50'],
            'paid_to' => ['nullable', 'string', 'max:120'],
            'expense_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:expense_date'],
            'notes' => ['nullable', 'string', 'max:4000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'remove_attachment' => ['nullable', 'boolean'],
        ]);
    }
}
