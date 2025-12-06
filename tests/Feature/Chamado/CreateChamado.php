<?php

namespace Tests\Feature\Chamado;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateChamado extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function test_user_can_create_chamado(): void
    {

        $this->seed();

        $user = User::factory()->create([
            'name' => 'Joao',
            'email' => 'joao@gmail.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->actingAs($user)->post('/api/chamados', [
            'titulo' => 'Resetar senha',
            'prioridade' => 'alta',
            'descricao' => 'Preciso resetar minha senha',
            'departamento_id' => '1',
            'categoria_id' => '2',
            'user_id' => '1',
        ]);

        $response->assertStatus(201);
    }
}
