<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Chamado;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class ChamadoModelTest extends TestCase
{
    use RefreshDatabase;
    public function test_comentarios_relation_exists()
    {
        $chamado = new Chamado();

        $relation = $chamado->comentarios();

        $this->assertInstanceOf(HasMany::class, $relation);
    }
}
