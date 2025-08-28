<?php

namespace App\Services;

use App\DTOs\InsertChamadoRequestDTO;
use App\DTOs\InsertChamadoResponseDTO;
use App\Models\Chamado;

class ChamadoCreateService
{
    public function criarChamado(InsertChamadoRequestDTO $DTO): InsertChamadoResponseDTO
    {
        try {
            $chamado = Chamado::create([
                'titulo' => $DTO->getTitulo(),
                'descricao' => $DTO->getDescricao(),
                'user_id' => $DTO->getUserId(),
                'prioridade' => $DTO->getPrioridade(),
                'categoria_id' => $DTO->getCategoriaId(),
                'departamento_id' => $DTO->getDepartamentoId(),
            ]);

            return InsertChamadoResponseDTO::success(
                data: [
                    'id' => $chamado->id,
                    'titulo' => $chamado->titulo,
                    'descricao' => $chamado->descricao,
                    'user_id' => $chamado->user_id,
                    'prioridade' => $chamado->prioridade,
                    'categoria_id' => $chamado->categoria_id,
                    'departamento_id' => $chamado->departamento_id,
                ],
                message: 'Chamado criado com sucesso!'
            );
        } catch (\Exception $e) {
            return InsertChamadoResponseDTO::error(
                message: 'Erro ao criar chamado',
                error: $e->getMessage()
            );
        }
    }
}