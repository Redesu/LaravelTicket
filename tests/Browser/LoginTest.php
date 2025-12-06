<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    // use DatabaseMigrations;
    use DatabaseTruncation;

    public function testUserCanLogin(): void
    {
        $user = User::factory()->create([
            'name' => 'Joao',
            'email' => 'joao@gmail.com',
            'password' => bcrypt('password')
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/auth/login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Entrar')
                ->assertPathIs('/');
        });
    }
}
