<?php

namespace Tests\Feature;

use App\Models\Income;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_open_another_users_income_record(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $income = Income::create([
            'user_id' => $otherUser->id,
            'category_id' => $otherUser->categories()->income()->firstOrFail()->id,
            'amount' => 2500,
            'status' => 'paid',
            'received_by' => 'bank',
            'received_from' => 'Hidden Client',
            'expected_date' => now()->toDateString(),
            'received_date' => now()->toDateString(),
        ]);

        $this->actingAs($owner)
            ->get(route('incomes.edit', $income))
            ->assertNotFound();
    }

    public function test_dashboard_only_shows_current_users_transactions(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        Income::create([
            'user_id' => $owner->id,
            'category_id' => $owner->categories()->income()->firstOrFail()->id,
            'amount' => 4000,
            'status' => 'paid',
            'received_by' => 'bank',
            'received_from' => 'Visible Client',
            'expected_date' => now()->toDateString(),
            'received_date' => now()->toDateString(),
        ]);

        Income::create([
            'user_id' => $otherUser->id,
            'category_id' => $otherUser->categories()->income()->firstOrFail()->id,
            'amount' => 9000,
            'status' => 'paid',
            'received_by' => 'bank',
            'received_from' => 'Hidden Client',
            'expected_date' => now()->toDateString(),
            'received_date' => now()->toDateString(),
        ]);

        $this->actingAs($owner)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Visible Client')
            ->assertDontSee('Hidden Client');
    }
}
