<?php

namespace Tests\Feature\Chamado;

use App\Models\Chamado;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditChamadoTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function test_user_can_edit_chamado(): void
    {
        $this->seed();
        $user = User::factory()->create([
            'name' => 'Joao',
            'email' => 'joao@gmail.com',
            'password' => bcrypt('password')
        ]);

        $chamado = Chamado::factory()->create([
            'titulo' => 'Resetar senha',
            'descricao' => 'Preciso resetar minha senha',
            'prioridade' => 'alta',
            'departamento_id' => '1',
            'categoria_id' => '2',
            'user_id' => $user->id,
            'created_by' => $user->id
        ]);

        $response = $this->actingAs($user)->put('/api/chamados/' . $chamado->id, [
            'titulo' => 'Resetar senha',
            'prioridade' => 'Urgente',
            'status' => 'aberto',
            'descricao' => 'Preciso resetar minha senha',
            'departamento_nome' => 'SUPORTE',
            'categoria_nome' => 'SUPORTE',
            'user_id' => '1',
        ]);

        $response->assertStatus(200);


    }
}
