<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'language' => 'fr',
        ]);

        $this->assertAuthenticated();
        $this->assertSame('fr', User::where('email', 'test@example.com')->value('language'));
        $this->assertSame('fr', session('language'));
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_authenticated_users_can_register_a_new_account(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post('/register', [
            'name' => 'Second User',
            'email' => 'second@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'client',
            'language' => 'en',
        ]);

        $this->assertAuthenticated();
        $this->assertAuthenticatedAs(User::where('email', 'second@example.com')->first());
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
