<?php

namespace App\Support;

use App\Models\Expense;
use App\Models\GivenLoan;
use App\Models\Income;
use App\Models\Reminder;
use App\Models\TakenLoan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReminderCenter
{
    public static function upcoming(User|int|null $user = null, int $days = 14): Collection
    {
        $endDate = now()->addDays($days)->endOfDay();

        $manualReminders = Reminder::query()
            ->ownedBy($user)
            ->where('status', 'pending')
            ->whereDate('reminder_date', '<=', $endDate)
            ->orderBy('reminder_date')
            ->get()
            ->map(function (Reminder $reminder): array {
                return self::formatReminder(
                    title: $reminder->title,
                    note: $reminder->notes,
                    amount: null,
                    date: $reminder->reminder_date,
                    type: $reminder->type,
                    origin: 'manual',
                    route: route('reminders.edit', $reminder),
                    channel: $reminder->channel,
                );
            });

        $incomeReminders = Income::query()
            ->with('category')
            ->ownedBy($user)
            ->where('status', 'pending')
            ->whereNotNull('expected_date')
            ->whereDate('expected_date', '<=', $endDate)
            ->orderBy('expected_date')
            ->get()
            ->map(function (Income $income): array {
                return self::formatReminder(
                    title: 'Expected income from '.($income->received_from ?: 'source'),
                    note: $income->category?->name ? 'Source: '.$income->category->name : $income->notes,
                    amount: (float) $income->amount,
                    date: $income->expected_date,
                    type: 'income',
                    origin: 'auto',
                    route: route('incomes.edit', $income),
                );
            });

        $expenseReminders = Expense::query()
            ->with('category')
            ->ownedBy($user)
            ->where('status', 'pending')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', $endDate)
            ->orderBy('due_date')
            ->get()
            ->map(function (Expense $expense): array {
                return self::formatReminder(
                    title: 'Expense due for '.($expense->category?->name ?: 'expense'),
                    note: $expense->paid_to ?: $expense->notes,
                    amount: (float) $expense->amount,
                    date: $expense->due_date,
                    type: 'expense',
                    origin: 'auto',
                    route: route('expenses.edit', $expense),
                );
            });

        $receivableReminders = GivenLoan::query()
            ->ownedBy($user)
            ->whereIn('status', ['pending', 'partial'])
            ->whereNotNull('expected_return_date')
            ->whereDate('expected_return_date', '<=', $endDate)
            ->orderBy('expected_return_date')
            ->get()
            ->map(function (GivenLoan $loan): array {
                return self::formatReminder(
                    title: 'Collect from '.$loan->person_name,
                    note: $loan->notes,
                    amount: $loan->outstanding_amount,
                    date: $loan->expected_return_date,
                    type: 'receivable',
                    origin: 'auto',
                    route: route('given-loans.edit', $loan),
                );
            });

        $payableReminders = TakenLoan::query()
            ->ownedBy($user)
            ->whereIn('status', ['pending', 'partial'])
            ->whereNotNull('return_date')
            ->whereDate('return_date', '<=', $endDate)
            ->orderBy('return_date')
            ->get()
            ->map(function (TakenLoan $loan): array {
                return self::formatReminder(
                    title: 'Return to '.$loan->person_name,
                    note: $loan->reason ?: $loan->notes,
                    amount: $loan->outstanding_amount,
                    date: $loan->return_date,
                    type: 'payable',
                    origin: 'auto',
                    route: route('taken-loans.edit', $loan),
                );
            });

        return $manualReminders
            ->concat($incomeReminders)
            ->concat($expenseReminders)
            ->concat($receivableReminders)
            ->concat($payableReminders)
            ->sortBy('date')
            ->values();
    }

    public static function countUpcoming(User|int|null $user = null, int $days = 14): int
    {
        return self::upcoming($user, $days)->count();
    }

    protected static function formatReminder(
        string $title,
        ?string $note,
        ?float $amount,
        Carbon|string|null $date,
        string $type,
        string $origin,
        string $route,
        string $channel = 'dashboard',
    ): array {
        $parsedDate = $date instanceof Carbon ? $date : ($date ? Carbon::parse($date) : now());

        return [
            'title' => $title,
            'note' => $note,
            'amount' => $amount,
            'date' => $parsedDate,
            'type' => $type,
            'origin' => $origin,
            'route' => $route,
            'channel' => $channel,
            'days_left' => now()->startOfDay()->diffInDays($parsedDate->copy()->startOfDay(), false),
        ];
    }
}
