<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\GivenLoan;
use App\Models\Income;
use App\Models\TakenLoan;
use App\Support\ReminderCenter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $totalIncome = (float) Income::query()->ownedBy($user)->where('status', 'paid')->sum('amount');
        $totalExpenses = (float) Expense::query()->ownedBy($user)->where('status', 'paid')->sum('amount');
        $totalGiven = (float) GivenLoan::query()->ownedBy($user)->sum('amount');
        $totalGivenReturned = (float) GivenLoan::query()->ownedBy($user)->sum('returned_amount');
        $totalTaken = (float) TakenLoan::query()->ownedBy($user)->sum('amount');
        $totalTakenPaid = (float) TakenLoan::query()->ownedBy($user)->sum('paid_amount');
        $pendingReceivables = GivenLoan::query()->ownedBy($user)->get()->sum('outstanding_amount');
        $pendingPayables = TakenLoan::query()->ownedBy($user)->get()->sum('outstanding_amount');

        $currentBalance = $totalIncome
            - $totalExpenses
            - $totalGiven
            + $totalGivenReturned
            + $totalTaken
            - $totalTakenPaid;

        $monthlyTrend = collect(range(5, 0))
            ->map(function (int $monthsAgo) use ($user): array {
                $month = now()->copy()->subMonths($monthsAgo);
                $start = $month->copy()->startOfMonth();
                $end = $month->copy()->endOfMonth();

                return [
                    'label' => $month->format('M Y'),
                    'income' => (float) Income::query()
                        ->ownedBy($user)
                        ->where('status', 'paid')
                        ->whereBetween('received_date', [$start->toDateString(), $end->toDateString()])
                        ->sum('amount'),
                    'expense' => (float) Expense::query()
                        ->ownedBy($user)
                        ->where('status', 'paid')
                        ->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()])
                        ->sum('amount'),
                ];
            })
            ->values();

        $expenseBreakdown = Expense::query()
            ->ownedBy($user)
            ->with('category')
            ->where('status', 'paid')
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get()
            ->take(6)
            ->map(fn (Expense $expense): array => [
                'label' => $expense->category?->name ?: 'Uncategorized',
                'total' => (float) $expense->total,
                'color' => $expense->category?->color ?: '#94a3b8',
            ])
            ->values();

        $recentTransactions = collect()
            ->concat(
                Income::query()->ownedBy($user)->with('category')->latest('received_date')->take(10)->get()->map(function (Income $income): array {
                    return [
                        'type' => 'Income',
                        'title' => $income->category?->name ?: 'Income',
                        'person' => $income->received_from ?: '-',
                        'amount' => (float) $income->amount,
                        'date' => $income->received_date ?: $income->expected_date ?: $income->created_at,
                        'status' => $income->status,
                        'route' => route('incomes.edit', $income),
                    ];
                })
            )
            ->concat(
                Expense::query()->ownedBy($user)->with('category')->latest('expense_date')->take(10)->get()->map(function (Expense $expense): array {
                    return [
                        'type' => 'Expense',
                        'title' => $expense->category?->name ?: 'Expense',
                        'person' => $expense->paid_to ?: '-',
                        'amount' => (float) $expense->amount,
                        'date' => $expense->expense_date ?: $expense->created_at,
                        'status' => $expense->status,
                        'route' => route('expenses.edit', $expense),
                    ];
                })
            )
            ->concat(
                GivenLoan::query()->ownedBy($user)->latest('given_date')->take(10)->get()->map(function (GivenLoan $loan): array {
                    return [
                        'type' => 'Given',
                        'title' => 'Loan to '.$loan->person_name,
                        'person' => $loan->person_name,
                        'amount' => (float) $loan->amount,
                        'date' => $loan->given_date ?: $loan->created_at,
                        'status' => $loan->status,
                        'route' => route('given-loans.edit', $loan),
                    ];
                })
            )
            ->concat(
                TakenLoan::query()->ownedBy($user)->latest('borrow_date')->take(10)->get()->map(function (TakenLoan $loan): array {
                    return [
                        'type' => 'Taken',
                        'title' => 'Borrowed from '.$loan->person_name,
                        'person' => $loan->person_name,
                        'amount' => (float) $loan->amount,
                        'date' => $loan->borrow_date ?: $loan->created_at,
                        'status' => $loan->status,
                        'route' => route('taken-loans.edit', $loan),
                    ];
                })
            )
            ->sortByDesc('date')
            ->take(10)
            ->values();

        $upcomingReminders = ReminderCenter::upcoming($user)->take(8)->values();

        return view('dashboard.index', [
            'stats' => [
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpenses,
                'current_balance' => $currentBalance,
                'total_given' => $totalGiven,
                'total_taken' => $totalTaken,
                'pending_receivables' => $pendingReceivables,
                'pending_payables' => $pendingPayables,
                'pending_income' => (float) Income::query()->ownedBy($user)->where('status', 'pending')->sum('amount'),
                'pending_expense' => (float) Expense::query()->ownedBy($user)->where('status', 'pending')->sum('amount'),
            ],
            'monthlyTrend' => $monthlyTrend,
            'expenseBreakdown' => $expenseBreakdown,
            'recentTransactions' => $recentTransactions,
            'upcomingReminders' => $upcomingReminders,
        ]);
    }
}
