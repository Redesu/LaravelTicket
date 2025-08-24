<?php

namespace App\Services;

use App\DTOs\InsertChamadoDTO;
use App\Models\Chamado;

class ChamadoCreateService
{
    public function criarChamado(InsertChamadoDTO $DTO): Chamado
    {
        return Chamado::create([
            'titulo' => $DTO->getTitulo(),
            'descricao' => $DTO->getDescricao(),
            'user_id' => $DTO->getUserId(),
            'prioridade' => $DTO->getPrioridade(),
            'categoria_id' => $DTO->getCategoriaId(),
            'departamento_id' => $DTO->getDepartamentoId(),
            'created_at' => now(),
        ]);
    }
}