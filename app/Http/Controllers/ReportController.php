<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\GivenLoan;
use App\Models\Income;
use App\Models\TakenLoan;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $data = $this->buildReportData($request->user(), $request->only([
            'date_from',
            'date_to',
            'category_id',
            'person',
            'status',
        ]));

        return view('reports.index', $data + [
            'categories' => Category::query()->ownedBy($request->user())->orderBy('type')->orderBy('name')->get(),
        ]);
    }

    public function export(Request $request): View|StreamedResponse
    {
        $data = $this->buildReportData($request->user(), $request->only([
            'date_from',
            'date_to',
            'category_id',
            'person',
            'status',
        ]));

        if ($request->query('format') === 'print') {
            return view('reports.print', $data);
        }

        $fileName = 'pmms-report-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($data): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['PMMS Report']);
            fputcsv($handle, ['Generated At', now()->format('d M Y h:i A')]);
            fputcsv($handle, []);

            fputcsv($handle, ['Summary']);
            foreach ($data['summary'] as $label => $value) {
                fputcsv($handle, [str_replace('_', ' ', ucfirst($label)), number_format($value, 2)]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Income']);
            fputcsv($handle, ['Source', 'From', 'Status', 'Amount', 'Expected Date', 'Received Date']);
            foreach ($data['incomes'] as $income) {
                fputcsv($handle, [
                    $income->category?->name,
                    $income->received_from,
                    ucfirst($income->status),
                    number_format((float) $income->amount, 2),
                    optional($income->expected_date)->format('Y-m-d'),
                    optional($income->received_date)->format('Y-m-d'),
                ]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Expenses']);
            fputcsv($handle, ['Category', 'Paid To', 'Status', 'Amount', 'Expense Date', 'Due Date']);
            foreach ($data['expenses'] as $expense) {
                fputcsv($handle, [
                    $expense->category?->name,
                    $expense->paid_to,
                    ucfirst($expense->status),
                    number_format((float) $expense->amount, 2),
                    optional($expense->expense_date)->format('Y-m-d'),
                    optional($expense->due_date)->format('Y-m-d'),
                ]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Given Money']);
            fputcsv($handle, ['Person', 'Amount', 'Returned', 'Outstanding', 'Status']);
            foreach ($data['givenLoans'] as $loan) {
                fputcsv($handle, [
                    $loan->person_name,
                    number_format((float) $loan->amount, 2),
                    number_format((float) $loan->returned_amount, 2),
                    number_format($loan->outstanding_amount, 2),
                    ucfirst($loan->status),
                ]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Taken Money']);
            fputcsv($handle, ['Person', 'Amount', 'Paid', 'Outstanding', 'Status']);
            foreach ($data['takenLoans'] as $loan) {
                fputcsv($handle, [
                    $loan->person_name,
                    number_format((float) $loan->amount, 2),
                    number_format((float) $loan->paid_amount, 2),
                    number_format($loan->outstanding_amount, 2),
                    ucfirst($loan->status),
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    protected function buildReportData(User $user, array $filters): array
    {
        $incomes = Income::query()
            ->ownedBy($user)
            ->with('category')
            ->filter($filters)
            ->orderByDesc('expected_date')
            ->get();

        $expenses = Expense::query()
            ->ownedBy($user)
            ->with('category')
            ->filter($filters)
            ->orderByDesc('expense_date')
            ->get();

        $givenLoans = GivenLoan::query()
            ->ownedBy($user)
            ->filter($filters)
            ->orderByDesc('given_date')
            ->get();

        $takenLoans = TakenLoan::query()
            ->ownedBy($user)
            ->filter($filters)
            ->orderByDesc('borrow_date')
            ->get();

        $latestCashFlow = collect(range(5, 0))
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

        $expenseByCategory = $expenses
            ->where('status', 'paid')
            ->groupBy(fn (Expense $expense) => $expense->category?->name ?: 'Uncategorized')
            ->map(function ($group, string $label) {
                $sample = $group->first();

                return [
                    'label' => $label,
                    'total' => $group->sum('amount'),
                    'color' => $sample?->category?->color ?: '#94a3b8',
                ];
            })
            ->sortByDesc('total')
            ->take(8)
            ->values();

        return [
            'filters' => $filters,
            'incomes' => $incomes,
            'expenses' => $expenses,
            'givenLoans' => $givenLoans,
            'takenLoans' => $takenLoans,
            'cashFlow' => $latestCashFlow,
            'expenseByCategory' => $expenseByCategory,
            'summary' => [
                'income' => (float) $incomes->sum('amount'),
                'expense' => (float) $expenses->sum('amount'),
                'receivable' => (float) $givenLoans->sum('outstanding_amount'),
                'payable' => (float) $takenLoans->sum('outstanding_amount'),
                'net_cash_flow' => (float) $incomes->where('status', 'paid')->sum('amount')
                    - (float) $expenses->where('status', 'paid')->sum('amount')
                    - (float) $givenLoans->sum('amount')
                    + (float) $givenLoans->sum('returned_amount')
                    + (float) $takenLoans->sum('amount')
                    - (float) $takenLoans->sum('paid_amount'),
            ],
        ];
    }
}
