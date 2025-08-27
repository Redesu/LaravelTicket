<?php
namespace App\Services;

use App\DTOs\AddComentarioRequestDTO;
use App\DTOs\AddComentarioResponseDTO;
use App\Models\Chamado;
use App\Models\ChamadoComentario;

class AddComentarioService
{
    public function addComentario(AddComentarioRequestDTO $request, int $id): AddComentarioResponseDTO
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

            return AddComentarioResponseDTO::success(
                comentario: $this->formatComentarioData($comentario),
                usuario: $this->formatUsuarioData($comentario->usuario),
                created_at: $comentario->created_at->format('d/m/Y H:i'),
                descricao: $comentario->descricao
            );
        } catch (\Exception $e) {
            return AddComentarioResponseDTO::error(
                message: 'Erro ao adicionar comentário:',
                error: $e->getMessage()
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