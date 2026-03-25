<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PageSmokeTest extends TestCase
{
    use RefreshDatabase;

    public static function pages(): array
    {
        return [
            'login' => ['/login'],
            'register' => ['/register'],
        ];
    }

    public static function protectedPages(): array
    {
        return [
            'dashboard' => ['/dashboard'],
            'profile' => ['/profile'],
            'categories' => ['/categories'],
            'income list' => ['/incomes'],
            'income create' => ['/incomes/create'],
            'expense list' => ['/expenses'],
            'expense create' => ['/expenses/create'],
            'given money' => ['/given-loans'],
            'taken money' => ['/taken-loans'],
            'reminders' => ['/reminders'],
            'reports' => ['/reports'],
            'search' => ['/search'],
            'backup' => ['/backup'],
        ];
    }

    #[DataProvider('pages')]
    public function test_guest_pages_render_successfully(string $uri): void
    {
        $this->get($uri)->assertOk();
    }

    #[DataProvider('protectedPages')]
    public function test_authenticated_pages_render_successfully(string $uri): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get($uri)
            ->assertOk();
    }
}
