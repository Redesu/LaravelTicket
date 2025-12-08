<?php

namespace Tests\Feature\Chamado;

use App\Models\Chamado;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteChamado extends TestCase
{
    use RefreshDatabase;

    public function user_can_delete_chamado(): void
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
            'user_id' => '1',
            'created_by' => '1'
        ]);


        $response = $this->actingAs($user)->delete('/api/chamados/' . $chamado->id);

        $response->assertStatus(200);
    }
}
