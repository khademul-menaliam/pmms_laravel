<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_default_categories(): void
    {
        $response = $this->post('/register', [
            'name' => 'New User',
            'email' => 'new-user@example.com',
            'phone' => '01711111111',
            'currency' => 'bdt',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'new-user@example.com',
            'currency' => 'BDT',
        ]);
        $this->assertDatabaseCount('categories', 11);
    }

    public function test_user_can_log_in_and_log_out(): void
    {
        $user = User::factory()->create([
            'email' => 'member@example.com',
            'password' => 'password',
        ]);

        $this->post('/login', [
            'email' => 'member@example.com',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->post('/logout')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_user_can_update_profile_and_password(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $this->actingAs($user)
            ->put('/profile', [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'phone' => '01999999999',
                'currency' => 'usd',
            ])->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'currency' => 'USD',
        ]);

        $this->actingAs($user->fresh())
            ->put('/profile/password', [
                'current_password' => 'password',
                'password' => 'newpassword1',
                'password_confirmation' => 'newpassword1',
            ])->assertRedirect(route('profile.edit'));

        $this->assertTrue(Hash::check('newpassword1', $user->fresh()->password));
    }
}
