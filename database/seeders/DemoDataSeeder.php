<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\GivenLoan;
use App\Models\Income;
use App\Models\Reminder;
use App\Models\TakenLoan;
use App\Models\User;
use App\Support\DefaultCategoryManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $demoUser = $this->createUser(
            name: 'Demo Owner',
            email: 'demo@pmms.test',
            phone: '01700000000',
            currency: 'BDT'
        );

        $secondUser = $this->createUser(
            name: 'Sadia Personal',
            email: 'sadia@pmms.test',
            phone: '01800000000',
            currency: 'BDT'
        );

        $this->seedPrimaryDataset($demoUser);
        $this->seedSecondaryDataset($secondUser);
    }

    protected function createUser(string $name, string $email, ?string $phone, string $currency): User
    {
        $user = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'phone' => $phone,
                'currency' => $currency,
                'password' => Hash::make('password'),
            ]
        );

        DefaultCategoryManager::ensureFor($user);

        return $user;
    }

    protected function seedPrimaryDataset(User $user): void
    {
        $incomeCategories = $user->categories()->where('type', 'income')->pluck('id', 'name');
        $expenseCategories = $user->categories()->where('type', 'expense')->pluck('id', 'name');

        $incomeRows = [
            ['category' => 'Salary', 'amount' => 55000, 'status' => 'paid', 'received_by' => 'bank', 'received_from' => 'TechNova Ltd.', 'expected_date' => now()->copy()->subMonths(4)->startOfMonth()->addDays(1), 'received_date' => now()->copy()->subMonths(4)->startOfMonth()->addDays(1), 'notes' => 'Monthly salary', 'is_recurring' => true, 'recurrence_cycle' => 'monthly'],
            ['category' => 'Salary', 'amount' => 55000, 'status' => 'paid', 'received_by' => 'bank', 'received_from' => 'TechNova Ltd.', 'expected_date' => now()->copy()->subMonths(3)->startOfMonth()->addDays(1), 'received_date' => now()->copy()->subMonths(3)->startOfMonth()->addDays(1), 'notes' => 'Monthly salary', 'is_recurring' => true, 'recurrence_cycle' => 'monthly'],
            ['category' => 'Freelance', 'amount' => 18000, 'status' => 'paid', 'received_by' => 'mobile_banking', 'received_from' => 'Pixel Forge Studio', 'expected_date' => now()->copy()->subMonths(2)->day(18), 'received_date' => now()->copy()->subMonths(2)->day(19), 'notes' => 'Landing page project payment', 'is_recurring' => false, 'recurrence_cycle' => null],
            ['category' => 'Business', 'amount' => 22000, 'status' => 'paid', 'received_by' => 'bank', 'received_from' => 'Local shop sales', 'expected_date' => now()->copy()->subMonth()->day(26), 'received_date' => now()->copy()->subMonth()->day(27), 'notes' => 'Monthly business profit', 'is_recurring' => false, 'recurrence_cycle' => null],
            ['category' => 'Freelance', 'amount' => 12000, 'status' => 'pending', 'received_by' => 'bank', 'received_from' => 'Green Peak Agency', 'expected_date' => now()->copy()->addDays(6), 'received_date' => null, 'notes' => 'Awaiting final milestone release', 'is_recurring' => false, 'recurrence_cycle' => null],
            ['category' => 'Investment', 'amount' => 7500, 'status' => 'paid', 'received_by' => 'bank', 'received_from' => 'Dividend payout', 'expected_date' => now()->copy()->day(10), 'received_date' => now()->copy()->day(10), 'notes' => 'Quarterly investment return', 'is_recurring' => false, 'recurrence_cycle' => null],
        ];

        foreach ($incomeRows as $row) {
            Income::create([
                'user_id' => $user->id,
                'category_id' => $incomeCategories[$row['category']],
                'amount' => $row['amount'],
                'status' => $row['status'],
                'received_by' => $row['received_by'],
                'received_from' => $row['received_from'],
                'expected_date' => $row['expected_date'],
                'received_date' => $row['received_date'],
                'notes' => $row['notes'],
                'is_recurring' => $row['is_recurring'],
                'recurrence_cycle' => $row['recurrence_cycle'],
            ]);
        }

        $expenseRows = [
            ['category' => 'Rent', 'amount' => 18000, 'status' => 'paid', 'paid_via' => 'bank', 'paid_to' => 'House Owner', 'expense_date' => now()->copy()->subMonths(4)->day(5), 'due_date' => now()->copy()->subMonths(4)->day(5), 'notes' => 'Monthly house rent'],
            ['category' => 'Utility', 'amount' => 3200, 'status' => 'paid', 'paid_via' => 'mobile_banking', 'paid_to' => 'DESCO', 'expense_date' => now()->copy()->subMonths(4)->day(8), 'due_date' => null, 'notes' => 'Electricity bill'],
            ['category' => 'Food', 'amount' => 6800, 'status' => 'paid', 'paid_via' => 'cash', 'paid_to' => 'Groceries', 'expense_date' => now()->copy()->subMonths(3)->day(14), 'due_date' => null, 'notes' => 'Family groceries'],
            ['category' => 'Internet', 'amount' => 1500, 'status' => 'paid', 'paid_via' => 'mobile_banking', 'paid_to' => 'FiberNet', 'expense_date' => now()->copy()->subMonths(3)->day(9), 'due_date' => null, 'notes' => 'Home internet bill'],
            ['category' => 'Transport', 'amount' => 2400, 'status' => 'paid', 'paid_via' => 'cash', 'paid_to' => 'Ride and fuel', 'expense_date' => now()->copy()->subMonths(2)->day(17), 'due_date' => null, 'notes' => 'Travel cost'],
            ['category' => 'Shopping', 'amount' => 5200, 'status' => 'paid', 'paid_via' => 'card', 'paid_to' => 'Lifestyle Store', 'expense_date' => now()->copy()->subMonths(2)->day(22), 'due_date' => null, 'notes' => 'Home and personal items'],
            ['category' => 'Food', 'amount' => 7200, 'status' => 'paid', 'paid_via' => 'cash', 'paid_to' => 'Groceries', 'expense_date' => now()->copy()->subMonth()->day(12), 'due_date' => null, 'notes' => 'Monthly groceries'],
            ['category' => 'Rent', 'amount' => 18000, 'status' => 'paid', 'paid_via' => 'bank', 'paid_to' => 'House Owner', 'expense_date' => now()->copy()->subMonth()->day(5), 'due_date' => now()->copy()->subMonth()->day(5), 'notes' => 'Monthly house rent'],
            ['category' => 'Utility', 'amount' => 3500, 'status' => 'pending', 'paid_via' => 'bank', 'paid_to' => 'DESCO', 'expense_date' => now()->copy()->day(25), 'due_date' => now()->copy()->addDays(4), 'notes' => 'Pending electricity bill'],
            ['category' => 'Internet', 'amount' => 1500, 'status' => 'paid', 'paid_via' => 'mobile_banking', 'paid_to' => 'FiberNet', 'expense_date' => now()->copy()->day(3), 'due_date' => null, 'notes' => 'Internet paid'],
            ['category' => 'Transport', 'amount' => 2800, 'status' => 'pending', 'paid_via' => 'cash', 'paid_to' => 'Fuel station', 'expense_date' => now()->copy()->day(18), 'due_date' => now()->copy()->addDays(2), 'notes' => 'Vehicle fuel pending entry'],
        ];

        foreach ($expenseRows as $row) {
            Expense::create([
                'user_id' => $user->id,
                'category_id' => $expenseCategories[$row['category']],
                'amount' => $row['amount'],
                'status' => $row['status'],
                'paid_via' => $row['paid_via'],
                'paid_to' => $row['paid_to'],
                'expense_date' => $row['expense_date'],
                'due_date' => $row['due_date'],
                'notes' => $row['notes'],
            ]);
        }

        GivenLoan::insert([
            [
                'user_id' => $user->id,
                'person_name' => 'Rahim',
                'amount' => 10000,
                'given_date' => now()->copy()->subMonths(3)->day(12),
                'expected_return_date' => now()->copy()->subMonths(2)->day(12),
                'status' => 'partial',
                'returned_amount' => 4000,
                'returned_date' => null,
                'notes' => 'Emergency family support',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'person_name' => 'Kamal',
                'amount' => 7000,
                'given_date' => now()->copy()->subMonth()->day(8),
                'expected_return_date' => now()->copy()->addDays(5),
                'status' => 'pending',
                'returned_amount' => 0,
                'returned_date' => null,
                'notes' => 'Short-term personal loan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'person_name' => 'Jannat',
                'amount' => 6000,
                'given_date' => now()->copy()->subMonths(2)->day(21),
                'expected_return_date' => now()->copy()->subMonth()->day(21),
                'status' => 'returned',
                'returned_amount' => 6000,
                'returned_date' => now()->copy()->subMonth()->day(22),
                'notes' => 'Returned on time',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        TakenLoan::insert([
            [
                'user_id' => $user->id,
                'person_name' => 'Arif',
                'amount' => 15000,
                'borrow_date' => now()->copy()->subMonths(2)->day(7),
                'return_date' => now()->copy()->addDays(8),
                'reason' => 'Laptop repair and emergency cash buffer',
                'status' => 'partial',
                'paid_amount' => 5000,
                'paid_date' => null,
                'notes' => 'Will clear after next salary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'person_name' => 'Nabila',
                'amount' => 8000,
                'borrow_date' => now()->copy()->subMonth()->day(10),
                'return_date' => now()->copy()->addDays(12),
                'reason' => 'Travel advance',
                'status' => 'pending',
                'paid_amount' => 0,
                'paid_date' => null,
                'notes' => 'Pending full payment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'person_name' => 'Hasan',
                'amount' => 5000,
                'borrow_date' => now()->copy()->subMonths(3)->day(16),
                'return_date' => now()->copy()->subMonths(2)->day(18),
                'reason' => 'Medical support',
                'status' => 'paid',
                'paid_amount' => 5000,
                'paid_date' => now()->copy()->subMonths(2)->day(18),
                'notes' => 'Closed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Reminder::insert([
            ['user_id' => $user->id, 'title' => 'Follow up freelance invoice', 'type' => 'income', 'channel' => 'dashboard', 'reminder_date' => now()->copy()->addDays(3), 'status' => 'pending', 'notes' => 'Check with Green Peak Agency about the pending milestone.', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $user->id, 'title' => 'Electricity bill due', 'type' => 'expense', 'channel' => 'dashboard', 'reminder_date' => now()->copy()->addDays(4), 'status' => 'pending', 'notes' => 'Pay before the due date to avoid late fees.', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $user->id, 'title' => 'Collect from Kamal', 'type' => 'receivable', 'channel' => 'notification', 'reminder_date' => now()->copy()->addDays(5), 'status' => 'pending', 'notes' => 'Friendly follow-up call in the evening.', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $user->id, 'title' => 'Return to Arif', 'type' => 'payable', 'channel' => 'dashboard', 'reminder_date' => now()->copy()->addDays(7), 'status' => 'pending', 'notes' => 'Plan partial payment from current month surplus.', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $user->id, 'title' => 'Renew internet package', 'type' => 'manual', 'channel' => 'dashboard', 'reminder_date' => now()->copy()->addDays(10), 'status' => 'pending', 'notes' => 'Check for upgraded plan before renewal.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    protected function seedSecondaryDataset(User $user): void
    {
        $incomeCategories = $user->categories()->where('type', 'income')->pluck('id', 'name');
        $expenseCategories = $user->categories()->where('type', 'expense')->pluck('id', 'name');

        Income::insert([
            [
                'user_id' => $user->id,
                'category_id' => $incomeCategories['Salary'],
                'amount' => 42000,
                'status' => 'paid',
                'received_by' => 'bank',
                'received_from' => 'BrightPath School',
                'expected_date' => now()->copy()->subMonth()->startOfMonth()->addDays(2),
                'received_date' => now()->copy()->subMonth()->startOfMonth()->addDays(2),
                'notes' => 'Teaching salary',
                'is_recurring' => true,
                'recurrence_cycle' => 'monthly',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'category_id' => $incomeCategories['Gift'],
                'amount' => 5000,
                'status' => 'paid',
                'received_by' => 'cash',
                'received_from' => 'Family gift',
                'expected_date' => now()->copy()->subDays(20),
                'received_date' => now()->copy()->subDays(20),
                'notes' => 'Festival gift',
                'is_recurring' => false,
                'recurrence_cycle' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Expense::insert([
            [
                'user_id' => $user->id,
                'category_id' => $expenseCategories['Food'],
                'amount' => 4800,
                'status' => 'paid',
                'paid_via' => 'cash',
                'paid_to' => 'Fresh market',
                'expense_date' => now()->copy()->subDays(18),
                'due_date' => null,
                'notes' => 'Home groceries',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'category_id' => $expenseCategories['Internet'],
                'amount' => 1200,
                'status' => 'pending',
                'paid_via' => 'mobile_banking',
                'paid_to' => 'NetLink',
                'expense_date' => now()->copy()->day(4),
                'due_date' => now()->copy()->addDays(6),
                'notes' => 'Internet renewal pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Reminder::create([
            'user_id' => $user->id,
            'title' => 'Pay internet bill',
            'type' => 'expense',
            'channel' => 'dashboard',
            'reminder_date' => now()->copy()->addDays(6),
            'status' => 'pending',
            'notes' => 'Use bKash before due date.',
        ]);
    }
}
