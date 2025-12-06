<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'name' => 'Joao',
            'email' => 'joao@gmail.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }
}
