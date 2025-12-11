<?php

use PHPUnit\Framework\TestCase;
use App\Models\ChamadoComentario;

class ChamadoComentarioModelTest extends TestCase
{
    public function test_changes_casts_to_array()
    {
        $comentario = new ChamadoComentario();

        $comentario->setRawAttributes(['changes' => json_encode(['key' => 'value'])], true);

        $this->assertIsArray($comentario->changes);
        $this->assertSame(['key' => 'value'], $comentario->changes);
    }
}
