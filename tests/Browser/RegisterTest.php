<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use DatabaseTruncation;
    public function testUserCanRegister(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/auth/register')
                ->type('name', 'Jane Doe')
                ->type('email', 'janedoe@zzz.com')
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('Registrar')
                ->assertPathIs('/');
        });
    }
}
