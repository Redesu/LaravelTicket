<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ChamadosTest extends DuskTestCase
{
    use DatabaseTruncation;
    protected function loginAsUser()
    {
        $user = User::factory()->create([
            'name' => 'Joao',
            'email' => 'joao@gmail.com',
            'password' => bcrypt('password')
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user);
        });

        return $user;
    }

    public function testUserCanSeeChamadosList()
    {
        $this->loginAsUser();
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/chamados')
                ->assertSee('Chamados');
        });
    }

    public function testUserCanCreateChamado()
    {
        $this->loginAsUser();

        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/chamados')
                ->waitForText('Chamados')
                ->press('Criar Chamado')
                ->pause(1000)
                ->type('titulo', 'Resetar senha')
                ->select('#prioridade', 'alta')
                ->type('descricao', 'Preciso resetar minha senha')
                ->select('#departamento_id', '1')
                ->select('#categoria_id', '2')
                ->select('#createChamadosUsuario', '1')
                ->press('Criar chamado')
                ->waitFor('.toast-success');
        });
    }
}