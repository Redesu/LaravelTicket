<?php
namespace App\Services\Solutions;

use App\DTOs\Solutions\Requests\CreateSolucaoRequestDTO;
use App\DTOs\Solutions\Responses\CreateSolucaoResponseDTO;
use App\Models\Chamado;
use App\Models\ChamadoComentario;
use Log;


class AddSolucaoService
{
    public function addSolucao(CreateSolucaoRequestDTO $request, int $id): CreateSolucaoResponseDTO
    {
        try {
            $chamado = Chamado::findOrFail($id);

            if ($chamado->hasSolution()) {
                return CreateSolucaoResponseDTO::error(
                    message: 'Chamado ja foi finalizado.',
                    error: 'Chamado ja foi finalizado.'
                );
            }

            $solucao = ChamadoComentario::create([
                'chamado_id' => $chamado->id,
                'usuario_id' => $request->getUsuarioId(),
                'descricao' => $request->getDesc(),
                'tipo' => $request->getTipo(),
            ]);

            $chamado->update(['status' => 'Finalizado']);
            $solucao->load('usuario');

            return CreateSolucaoResponseDTO::success(
                comentario: $this->formatSolucaoData($solucao),
                message: 'Solução adicionada e chamado marcado como resolvido.',
            );
        } catch (\Exception $e) {
            Log::error('Erro ao adicionar solução: ' . $e->getMessage());
            return CreateSolucaoResponseDTO::error(
                message: 'Erro ao adicionar solução',
                error: 'Ocorreu um erro ao adicionar a solução.'
            );
        }
    }

    private function formatSolucaoData(ChamadoComentario $solucao): array
    {
        return [
            'id' => $solucao->id,
            'descricao' => $solucao->descricao,
            'tipo' => $solucao->tipo,
        ];
    }
}