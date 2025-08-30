<?php
namespace App\Services\Comments;

use App\DTOs\Comments\Requests\CreateComentarioRequestDTO;
use App\DTOs\Comments\Responses\CreateComentarioResponseDTO;
use App\Models\Chamado;
use App\Models\ChamadoComentario;
use Log;

class AddComentarioService
{
    public function addComentario(CreateComentarioRequestDTO $request, int $id): CreateComentarioResponseDTO
    {
        try {
            $chamado = Chamado::findOrFail($id);

            $comentario = ChamadoComentario::create([
                'chamado_id' => $chamado->id,
                'usuario_id' => $request->getUsuarioId(),
                'descricao' => $request->getDesc(),
                'tipo' => $request->getTipo(),
                'changes' => $request->getChanges(),
            ]);

            $comentario->load('usuario');

            return CreateComentarioResponseDTO::success(
                comentario: $this->formatComentarioData($comentario),
                usuario: $this->formatUsuarioData($comentario->usuario),
                created_at: $comentario->created_at->format('d/m/Y H:i'),
                descricao: $comentario->descricao
            );
        } catch (\Exception $e) {
            Log::error('Error ao adicionar comentário: ' . $e->getMessage());
            return CreateComentarioResponseDTO::error(
                message: 'Erro ao adicionar comentário:',
                error: 'Não foi possivel adicionar o comentário'
            );
        }
    }
    private function formatComentarioData(ChamadoComentario $comentario): array
    {
        return [
            'id' => $comentario->id,
            'chamado_id' => $comentario->chamado_id,
            'descricao' => $comentario->descricao,
            'tipo' => $comentario->tipo,
            'changes' => $comentario->changes,
        ];
    }

    private function formatUsuarioData($usuario): array
    {
        return [
            'name' => $usuario->name,
            'avatar' => $usuario->avatar
        ];
    }
}