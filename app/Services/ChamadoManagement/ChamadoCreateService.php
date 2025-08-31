<?php

namespace App\Services\ChamadoManagement;

use App\DTOs\ChamadoManagement\Requests\CreateChamadoRequestDTO;
use App\DTOs\ChamadoManagement\Responses\CreateChamadoResponseDTO;
use App\Models\Chamado;
use Log;

class ChamadoCreateService
{
    public function criarChamado(CreateChamadoRequestDTO $DTO): CreateChamadoResponseDTO
    {
        try {
            $chamado = Chamado::create([
                'titulo' => $DTO->getTitulo(),
                'descricao' => $DTO->getDescricao(),
                'user_id' => $DTO->getUserId(),
                'created_by' => auth()->user()->id,
                'prioridade' => $DTO->getPrioridade(),
                'categoria_id' => $DTO->getCategoriaId(),
                'departamento_id' => $DTO->getDepartamentoId(),
            ]);

            return CreateChamadoResponseDTO::success(
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
            Log::error('Error ao criar chamado: ' . $e->getMessage());
            return CreateChamadoResponseDTO::error(
                message: 'Erro ao criar chamado',
                error: 'Ocorreu um erro ao criar o chamado. Por favor, tente novamente.'
            );
        }
    }
}