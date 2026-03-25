<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\GivenLoan;
use App\Models\Income;
use App\Models\TakenLoan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $filters = $request->only([
            'date_from',
            'date_to',
            'amount_min',
            'amount_max',
            'category_id',
            'person',
            'status',
        ]);

        $hasFilters = collect($filters)->filter(fn ($value) => filled($value))->isNotEmpty();
        $limit = $hasFilters ? 30 : 8;

        $incomeResults = Income::query()
            ->ownedBy($user)
            ->with('category')
            ->filter($filters)
            ->orderByDesc('expected_date')
            ->limit($limit)
            ->get();

        $expenseResults = Expense::query()
            ->ownedBy($user)
            ->with('category')
            ->filter($filters)
            ->orderByDesc('expense_date')
            ->limit($limit)
            ->get();

        $givenResults = GivenLoan::query()
            ->ownedBy($user)
            ->filter($filters)
            ->orderByDesc('given_date')
            ->limit($limit)
            ->get();

        $takenResults = TakenLoan::query()
            ->ownedBy($user)
            ->filter($filters)
            ->orderByDesc('borrow_date')
            ->limit($limit)
            ->get();

        return view('search.index', [
            'filters' => $filters,
            'hasFilters' => $hasFilters,
            'categories' => Category::query()->ownedBy($user)->orderBy('type')->orderBy('name')->get(),
            'results' => [
                'incomes' => $incomeResults,
                'expenses' => $expenseResults,
                'given' => $givenResults,
                'taken' => $takenResults,
            ],
            'counts' => [
                'incomes' => $incomeResults->count(),
                'expenses' => $expenseResults->count(),
                'given' => $givenResults->count(),
                'taken' => $takenResults->count(),
            ],
        ]);
    }
}
