<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Expense;
use App\Models\GivenLoan;
use App\Models\Income;
use App\Models\Reminder;
use App\Models\TakenLoan;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupManager
{
    public static function directoryFor(User $user): string
    {
        return 'backups/user-'.$user->getKey();
    }

    public static function payload(User $user): array
    {
        return [
            'exported_at' => now()->toIso8601String(),
            'application' => config('app.name'),
            'categories' => Category::query()->ownedBy($user)->orderBy('id')->get()->toArray(),
            'incomes' => Income::query()->ownedBy($user)->orderBy('id')->get()->toArray(),
            'expenses' => Expense::query()->ownedBy($user)->orderBy('id')->get()->toArray(),
            'given_loans' => GivenLoan::query()->ownedBy($user)->orderBy('id')->get()->toArray(),
            'taken_loans' => TakenLoan::query()->ownedBy($user)->orderBy('id')->get()->toArray(),
            'reminders' => Reminder::query()->ownedBy($user)->orderBy('id')->get()->toArray(),
        ];
    }

    public static function storeSnapshot(User $user, ?string $filename = null): string
    {
        $filename ??= 'pmms-backup-'.now()->format('Ymd-His').'.json';
        $directory = self::directoryFor($user);

        Storage::disk('local')->makeDirectory($directory);
        Storage::disk('local')->put(
            $directory.'/'.$filename,
            json_encode(self::payload($user), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        return $directory.'/'.$filename;
    }

    public static function import(User $user, array $payload): void
    {
        DB::transaction(function () use ($payload, $user): void {
            $categoryMap = [];

            foreach ($payload['categories'] ?? [] as $categoryData) {
                $category = Category::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'slug' => $categoryData['slug'],
                        'type' => $categoryData['type'],
                    ],
                    array_merge(
                        Arr::except($categoryData, ['id', 'user_id', 'created_at', 'updated_at']),
                        ['user_id' => $user->id]
                    )
                );

                $categoryMap[$categoryData['id']] = $category->id;
            }

            foreach ($payload['incomes'] ?? [] as $incomeData) {
                $data = Arr::except($incomeData, ['id', 'user_id', 'created_at', 'updated_at']);
                $data['category_id'] = $categoryMap[$incomeData['category_id'] ?? null] ?? null;
                $data['user_id'] = $user->id;

                Income::create($data);
            }

            foreach ($payload['expenses'] ?? [] as $expenseData) {
                $data = Arr::except($expenseData, ['id', 'user_id', 'created_at', 'updated_at']);
                $data['category_id'] = $categoryMap[$expenseData['category_id'] ?? null] ?? null;
                $data['user_id'] = $user->id;

                Expense::create($data);
            }

            foreach ($payload['given_loans'] ?? [] as $loanData) {
                GivenLoan::create(array_merge(
                    Arr::except($loanData, ['id', 'user_id', 'created_at', 'updated_at', 'outstanding_amount']),
                    ['user_id' => $user->id]
                ));
            }

            foreach ($payload['taken_loans'] ?? [] as $loanData) {
                TakenLoan::create(array_merge(
                    Arr::except($loanData, ['id', 'user_id', 'created_at', 'updated_at', 'outstanding_amount']),
                    ['user_id' => $user->id]
                ));
            }

            foreach ($payload['reminders'] ?? [] as $reminderData) {
                Reminder::create(array_merge(
                    Arr::except($reminderData, ['id', 'user_id', 'created_at', 'updated_at']),
                    ['user_id' => $user->id]
                ));
            }
        });
    }
}
