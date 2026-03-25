<?php

namespace App\Http\Controllers;

use App\Models\GivenLoan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GivenLoanController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['person', 'status', 'date_from', 'date_to']);

        $query = GivenLoan::query()
            ->ownedBy($request->user())
            ->filter($filters)
            ->orderByDesc('given_date')
            ->orderByDesc('created_at');

        $loans = (clone $query)
            ->paginate(12)
            ->withQueryString();

        return view('given-loans.index', [
            'loans' => $loans,
            'filters' => $filters,
            'summary' => [
                'given' => (clone $query)->sum('amount'),
                'returned' => (clone $query)->sum('returned_amount'),
                'outstanding' => (clone $query)->get()->sum('outstanding_amount'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('given-loans.create', [
            'loan' => new GivenLoan(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        GivenLoan::create($this->prepareData($request));

        return redirect()
            ->route('given-loans.index')
            ->with('success', 'Given money record added successfully.');
    }

    public function show(string $id): never
    {
        abort(404);
    }

    public function edit(Request $request, string $given_loan): View
    {
        /** @var \App\Models\GivenLoan $givenLoan */
        $givenLoan = $this->resolveOwnedModel($request, 'given_loan', GivenLoan::class);

        return view('given-loans.edit', [
            'loan' => $givenLoan,
        ]);
    }

    public function update(Request $request, string $given_loan): RedirectResponse
    {
        /** @var \App\Models\GivenLoan $givenLoan */
        $givenLoan = $this->resolveOwnedModel($request, 'given_loan', GivenLoan::class);

        $this->ensureOwned($givenLoan);

        $givenLoan->update($this->prepareData($request));

        return redirect()
            ->route('given-loans.index')
            ->with('success', 'Given money record updated successfully.');
    }

    public function destroy(Request $request, string $given_loan): RedirectResponse
    {
        /** @var \App\Models\GivenLoan $givenLoan */
        $givenLoan = $this->resolveOwnedModel($request, 'given_loan', GivenLoan::class);

        $this->ensureOwned($givenLoan);

        $givenLoan->delete();

        return redirect()
            ->route('given-loans.index')
            ->with('success', 'Given money record deleted successfully.');
    }

    public function markReturned(Request $request, string $givenLoan): RedirectResponse
    {
        /** @var \App\Models\GivenLoan $givenLoan */
        $givenLoan = $this->resolveOwnedModel($request, 'givenLoan', GivenLoan::class);

        $givenLoan->update([
            'status' => 'returned',
            'returned_amount' => $givenLoan->amount,
            'returned_date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Loan marked as returned.');
    }

    protected function prepareData(Request $request): array
    {
        $data = $request->validate([
            'person_name' => ['required', 'string', 'max:120'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'given_date' => ['required', 'date'],
            'expected_return_date' => ['nullable', 'date', 'after_or_equal:given_date'],
            'returned_amount' => ['nullable', 'numeric', 'min:0'],
            'returned_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:4000'],
        ]);

        $returnedAmount = min((float) ($data['returned_amount'] ?? 0), (float) $data['amount']);
        $data['returned_amount'] = $returnedAmount;

        if ($returnedAmount <= 0) {
            $data['status'] = 'pending';
            $data['returned_date'] = null;
        } elseif ($returnedAmount < (float) $data['amount']) {
            $data['status'] = 'partial';
            $data['returned_date'] = null;
        } else {
            $data['status'] = 'returned';
            $data['returned_date'] = $data['returned_date'] ?? now()->toDateString();
        }

        return $data;
    }
}
