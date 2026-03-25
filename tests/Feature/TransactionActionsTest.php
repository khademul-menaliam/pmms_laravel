<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\GivenLoan;
use App\Models\Income;
use App\Models\Reminder;
use App\Models\TakenLoan;
use App\Models\User;
use App\Support\ReminderCenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_pages_load_for_owned_records(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Bonus',
            'slug' => 'bonus',
            'type' => 'income',
            'color' => '#4f46e5',
        ]);

        $income = Income::create([
            'user_id' => $user->id,
            'category_id' => $user->categories()->income()->where('is_default', true)->firstOrFail()->id,
            'amount' => 1200,
            'status' => 'pending',
            'expected_date' => now()->toDateString(),
        ]);

        $expense = Expense::create([
            'user_id' => $user->id,
            'category_id' => $user->categories()->expense()->where('is_default', true)->firstOrFail()->id,
            'amount' => 800,
            'status' => 'pending',
            'expense_date' => now()->toDateString(),
            'due_date' => now()->addDays(2)->toDateString(),
        ]);

        $givenLoan = GivenLoan::create([
            'user_id' => $user->id,
            'person_name' => 'Rahim',
            'amount' => 3000,
            'given_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        $takenLoan = TakenLoan::create([
            'user_id' => $user->id,
            'person_name' => 'Karim',
            'amount' => 2500,
            'borrow_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        $reminder = Reminder::create([
            'user_id' => $user->id,
            'title' => 'Call bank',
            'type' => 'manual',
            'channel' => 'dashboard',
            'reminder_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
        ]);

        $this->actingAs($user)->get(route('categories.edit', $category))->assertOk();
        $this->actingAs($user)->get(route('incomes.edit', $income))->assertOk();
        $this->actingAs($user)->get(route('expenses.edit', $expense))->assertOk();
        $this->actingAs($user)->get(route('given-loans.edit', $givenLoan))->assertOk();
        $this->actingAs($user)->get(route('taken-loans.edit', $takenLoan))->assertOk();
        $this->actingAs($user)->get(route('reminders.edit', $reminder))->assertOk();
    }

    public function test_update_actions_save_owned_records(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Bonus',
            'slug' => 'bonus',
            'type' => 'income',
            'color' => '#4f46e5',
        ]);

        $income = Income::create([
            'user_id' => $user->id,
            'category_id' => $user->categories()->income()->where('is_default', true)->firstOrFail()->id,
            'amount' => 1200,
            'status' => 'pending',
            'expected_date' => now()->toDateString(),
        ]);

        $expense = Expense::create([
            'user_id' => $user->id,
            'category_id' => $user->categories()->expense()->where('is_default', true)->firstOrFail()->id,
            'amount' => 800,
            'status' => 'pending',
            'expense_date' => now()->toDateString(),
        ]);

        $givenLoan = GivenLoan::create([
            'user_id' => $user->id,
            'person_name' => 'Rahim',
            'amount' => 3000,
            'given_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        $takenLoan = TakenLoan::create([
            'user_id' => $user->id,
            'person_name' => 'Karim',
            'amount' => 2500,
            'borrow_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        $reminder = Reminder::create([
            'user_id' => $user->id,
            'title' => 'Call bank',
            'type' => 'manual',
            'channel' => 'dashboard',
            'reminder_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
        ]);

        $this->actingAs($user)->put(route('categories.update', $category), [
            'name' => 'Updated Bonus',
            'type' => 'income',
            'color' => '#0ea5e9',
            'description' => 'Updated category',
        ])->assertRedirect(route('categories.index'));

        $this->actingAs($user)->put(route('incomes.update', $income), [
            'category_id' => $user->categories()->income()->where('is_default', true)->firstOrFail()->id,
            'amount' => 1800,
            'status' => 'paid',
            'received_by' => 'bank',
            'received_from' => 'Updated Client',
            'expected_date' => now()->toDateString(),
            'received_date' => now()->toDateString(),
            'notes' => 'Updated income',
            'recurrence_cycle' => '',
        ])->assertRedirect(route('incomes.index'));

        $this->actingAs($user)->put(route('expenses.update', $expense), [
            'category_id' => $user->categories()->expense()->where('is_default', true)->firstOrFail()->id,
            'amount' => 950,
            'status' => 'paid',
            'paid_via' => 'cash',
            'paid_to' => 'Updated Vendor',
            'expense_date' => now()->toDateString(),
            'due_date' => '',
            'notes' => 'Updated expense',
        ])->assertRedirect(route('expenses.index'));

        $this->actingAs($user)->put(route('given-loans.update', $givenLoan), [
            'person_name' => 'Updated Rahim',
            'amount' => 3500,
            'given_date' => now()->toDateString(),
            'expected_return_date' => now()->addDays(5)->toDateString(),
            'returned_amount' => 500,
            'returned_date' => '',
            'notes' => 'Updated loan',
        ])->assertRedirect(route('given-loans.index'));

        $this->actingAs($user)->put(route('taken-loans.update', $takenLoan), [
            'person_name' => 'Updated Karim',
            'amount' => 2800,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(5)->toDateString(),
            'reason' => 'Updated reason',
            'paid_amount' => 600,
            'paid_date' => '',
            'notes' => 'Updated taken loan',
        ])->assertRedirect(route('taken-loans.index'));

        $this->actingAs($user)->put(route('reminders.update', $reminder), [
            'title' => 'Updated reminder',
            'type' => 'manual',
            'channel' => 'dashboard',
            'reminder_date' => now()->addDays(3)->toDateString(),
            'status' => 'pending',
            'notes' => 'Updated reminder note',
        ])->assertRedirect(route('reminders.index'));

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated Bonus']);
        $this->assertDatabaseHas('incomes', ['id' => $income->id, 'received_from' => 'Updated Client', 'status' => 'paid']);
        $this->assertDatabaseHas('expenses', ['id' => $expense->id, 'paid_to' => 'Updated Vendor', 'status' => 'paid']);
        $this->assertDatabaseHas('given_loans', ['id' => $givenLoan->id, 'person_name' => 'Updated Rahim', 'status' => 'partial']);
        $this->assertDatabaseHas('taken_loans', ['id' => $takenLoan->id, 'person_name' => 'Updated Karim', 'status' => 'partial']);
        $this->assertDatabaseHas('reminders', ['id' => $reminder->id, 'title' => 'Updated reminder']);
    }

    public function test_mark_actions_update_owned_records(): void
    {
        $user = User::factory()->create();

        $income = Income::create([
            'user_id' => $user->id,
            'category_id' => $user->categories()->income()->where('is_default', true)->firstOrFail()->id,
            'amount' => 1200,
            'status' => 'pending',
            'expected_date' => now()->toDateString(),
        ]);

        $expense = Expense::create([
            'user_id' => $user->id,
            'category_id' => $user->categories()->expense()->where('is_default', true)->firstOrFail()->id,
            'amount' => 800,
            'status' => 'pending',
            'expense_date' => now()->toDateString(),
            'due_date' => now()->addDays(2)->toDateString(),
        ]);

        $givenLoan = GivenLoan::create([
            'user_id' => $user->id,
            'person_name' => 'Rahim',
            'amount' => 3000,
            'given_date' => now()->toDateString(),
            'status' => 'pending',
            'returned_amount' => 0,
        ]);

        $takenLoan = TakenLoan::create([
            'user_id' => $user->id,
            'person_name' => 'Karim',
            'amount' => 2500,
            'borrow_date' => now()->toDateString(),
            'status' => 'pending',
            'paid_amount' => 0,
        ]);

        $reminder = Reminder::create([
            'user_id' => $user->id,
            'title' => 'Call bank',
            'type' => 'manual',
            'channel' => 'dashboard',
            'reminder_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->from(route('incomes.index'))
            ->patch(route('incomes.mark-paid', $income))
            ->assertRedirect(route('incomes.index'));

        $this->actingAs($user)
            ->from(route('expenses.index'))
            ->patch(route('expenses.mark-paid', $expense))
            ->assertRedirect(route('expenses.index'));

        $this->actingAs($user)
            ->from(route('given-loans.index'))
            ->patch(route('given-loans.mark-returned', $givenLoan))
            ->assertRedirect(route('given-loans.index'));

        $this->actingAs($user)
            ->from(route('taken-loans.index'))
            ->patch(route('taken-loans.mark-paid', $takenLoan))
            ->assertRedirect(route('taken-loans.index'));

        $this->actingAs($user)
            ->from(route('reminders.index'))
            ->patch(route('reminders.mark-complete', $reminder))
            ->assertRedirect(route('reminders.index'));

        $this->assertDatabaseHas('incomes', ['id' => $income->id, 'status' => 'paid']);
        $this->assertDatabaseHas('expenses', ['id' => $expense->id, 'status' => 'paid']);
        $this->assertDatabaseHas('given_loans', ['id' => $givenLoan->id, 'status' => 'returned']);
        $this->assertDatabaseHas('taken_loans', ['id' => $takenLoan->id, 'status' => 'paid']);
        $this->assertDatabaseHas('reminders', ['id' => $reminder->id, 'status' => 'completed']);
    }

    public function test_delete_actions_remove_owned_records(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Bonus',
            'slug' => 'bonus-delete',
            'type' => 'income',
            'color' => '#4f46e5',
        ]);

        $income = Income::create([
            'user_id' => $user->id,
            'category_id' => $user->categories()->income()->where('is_default', true)->firstOrFail()->id,
            'amount' => 1200,
            'status' => 'pending',
            'expected_date' => now()->toDateString(),
        ]);

        $expense = Expense::create([
            'user_id' => $user->id,
            'category_id' => $user->categories()->expense()->firstOrFail()->id,
            'amount' => 800,
            'status' => 'pending',
            'expense_date' => now()->toDateString(),
        ]);

        $givenLoan = GivenLoan::create([
            'user_id' => $user->id,
            'person_name' => 'Rahim',
            'amount' => 3000,
            'given_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        $takenLoan = TakenLoan::create([
            'user_id' => $user->id,
            'person_name' => 'Karim',
            'amount' => 2500,
            'borrow_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        $reminder = Reminder::create([
            'user_id' => $user->id,
            'title' => 'Call bank',
            'type' => 'manual',
            'channel' => 'dashboard',
            'reminder_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
        ]);

        $this->actingAs($user)->delete(route('categories.destroy', $category))->assertRedirect(route('categories.index'));
        $this->actingAs($user)->delete(route('incomes.destroy', $income))->assertRedirect(route('incomes.index'));
        $this->actingAs($user)->delete(route('expenses.destroy', $expense))->assertRedirect(route('expenses.index'));
        $this->actingAs($user)->delete(route('given-loans.destroy', $givenLoan))->assertRedirect(route('given-loans.index'));
        $this->actingAs($user)->delete(route('taken-loans.destroy', $takenLoan))->assertRedirect(route('taken-loans.index'));
        $this->actingAs($user)->delete(route('reminders.destroy', $reminder))->assertRedirect(route('reminders.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseMissing('incomes', ['id' => $income->id]);
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
        $this->assertDatabaseMissing('given_loans', ['id' => $givenLoan->id]);
        $this->assertDatabaseMissing('taken_loans', ['id' => $takenLoan->id]);
        $this->assertDatabaseMissing('reminders', ['id' => $reminder->id]);
    }

    public function test_auto_reminder_links_open_the_related_edit_page(): void
    {
        $user = User::factory()->create();

        Income::create([
            'user_id' => $user->id,
            'category_id' => $user->categories()->income()->firstOrFail()->id,
            'amount' => 1200,
            'status' => 'pending',
            'received_from' => 'Reminder Client',
            'expected_date' => now()->addDays(2)->toDateString(),
        ]);

        $reminderLink = ReminderCenter::upcoming($user, 30)
            ->first(fn (array $item) => $item['origin'] === 'auto');

        $this->assertNotNull($reminderLink);

        $this->actingAs($user)
            ->get($reminderLink['route'])
            ->assertOk();
    }
}
