<?php

namespace App\Http\Controllers;

use App\Models\TakenLoan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TakenLoanController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['person', 'status', 'date_from', 'date_to']);

        $query = TakenLoan::query()
            ->ownedBy($request->user())
            ->filter($filters)
            ->orderByDesc('borrow_date')
            ->orderByDesc('created_at');

        $loans = (clone $query)
            ->paginate(12)
            ->withQueryString();

        return view('taken-loans.index', [
            'loans' => $loans,
            'filters' => $filters,
            'summary' => [
                'borrowed' => (clone $query)->sum('amount'),
                'paid' => (clone $query)->sum('paid_amount'),
                'outstanding' => (clone $query)->get()->sum('outstanding_amount'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('taken-loans.create', [
            'loan' => new TakenLoan(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        TakenLoan::create($this->prepareData($request));

        return redirect()
            ->route('taken-loans.index')
            ->with('success', 'Taken money record added successfully.');
    }

    public function show(string $id): never
    {
        abort(404);
    }

    public function edit(Request $request, string $taken_loan): View
    {
        /** @var \App\Models\TakenLoan $takenLoan */
        $takenLoan = $this->resolveOwnedModel($request, 'taken_loan', TakenLoan::class);

        return view('taken-loans.edit', [
            'loan' => $takenLoan,
        ]);
    }

    public function update(Request $request, string $taken_loan): RedirectResponse
    {
        /** @var \App\Models\TakenLoan $takenLoan */
        $takenLoan = $this->resolveOwnedModel($request, 'taken_loan', TakenLoan::class);

        $this->ensureOwned($takenLoan);

        $takenLoan->update($this->prepareData($request));

        return redirect()
            ->route('taken-loans.index')
            ->with('success', 'Taken money record updated successfully.');
    }

    public function destroy(Request $request, string $taken_loan): RedirectResponse
    {
        /** @var \App\Models\TakenLoan $takenLoan */
        $takenLoan = $this->resolveOwnedModel($request, 'taken_loan', TakenLoan::class);

        $this->ensureOwned($takenLoan);

        $takenLoan->delete();

        return redirect()
            ->route('taken-loans.index')
            ->with('success', 'Taken money record deleted successfully.');
    }

    public function markPaid(Request $request, string $takenLoan): RedirectResponse
    {
        /** @var \App\Models\TakenLoan $takenLoan */
        $takenLoan = $this->resolveOwnedModel($request, 'takenLoan', TakenLoan::class);

        $takenLoan->update([
            'status' => 'paid',
            'paid_amount' => $takenLoan->amount,
            'paid_date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Loan marked as paid.');
    }

    protected function prepareData(Request $request): array
    {
        $data = $request->validate([
            'person_name' => ['required', 'string', 'max:120'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'borrow_date' => ['required', 'date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:borrow_date'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'paid_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:4000'],
        ]);

        $paidAmount = min((float) ($data['paid_amount'] ?? 0), (float) $data['amount']);
        $data['paid_amount'] = $paidAmount;

        if ($paidAmount <= 0) {
            $data['status'] = 'pending';
            $data['paid_date'] = null;
        } elseif ($paidAmount < (float) $data['amount']) {
            $data['status'] = 'partial';
            $data['paid_date'] = null;
        } else {
            $data['status'] = 'paid';
            $data['paid_date'] = $data['paid_date'] ?? now()->toDateString();
        }

        return $data;
    }
}
