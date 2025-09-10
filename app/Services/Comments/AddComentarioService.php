<?php
namespace App\Services\Comments;

use App\DTOs\Comments\Requests\CreateComentarioRequestDTO;
use App\DTOs\Comments\Responses\CreateComentarioResponseDTO;
use App\Models\Chamado;
use App\Models\ChamadoComentario;
use App\Services\Anexos\ProcessAnexosService;
use Carbon\Exceptions\InvalidTypeException;
use DB;
use Dotenv\Exception\InvalidFileException;
use Exception;
use Log;

class AddComentarioService
{
    public function __construct(
        private ProcessAnexosService $processAnexosService
    ) {
    }

    public function addComentario(CreateComentarioRequestDTO $request, int $id): CreateComentarioResponseDTO
    {
        DB::beginTransaction();
        try {
            $chamado = Chamado::findOrFail($id);

            if ($chamado->hasSolution()) {
                return CreateComentarioResponseDTO::error(
                    message: 'Erro ao adicionar comentário:',
                    error: 'Não é possivel adicionar comentários em chamados fechados'
                );
            }

            $comentario = ChamadoComentario::create([
                'chamado_id' => $chamado->id,
                'usuario_id' => $request->getUsuarioId(),
                'descricao' => $request->getDesc(),
                'tipo' => $request->getTipo(),
                'changes' => $request->getChanges(),
            ]);

            if ($request->getAnexos()) {
                $this->processAnexosService->processAnexos($request->getAnexos(), $comentario);
            }

            $comentario->load('usuario');
            DB::commit();

            return CreateComentarioResponseDTO::success(
                comentario: $this->formatComentarioData($comentario),
                usuario: $this->formatUsuarioData($comentario->usuario),
                created_at: $comentario->created_at->format('d/m/Y H:i'),
                descricao: $comentario->descricao
            );
        } catch (InvalidFileException $e) {
            DB::rollBack();
            Log::warning('Arquivo inválido ao criar comentário: ' . $e->getMessage());
            return CreateComentarioResponseDTO::error(
                message: 'Arquivo Inválido',
                error: $e->getMessage()
            );
        } catch (InvalidTypeException $e) {
            DB::rollBack();
            Log::warning('Tipo de arquivo inválido ao criar comentário: ' . $e->getMessage());
            return CreateComentarioResponseDTO::error(
                message: 'Tipo de arquivo Inválido ao criar comentario',
                error: $e->getMessage()
            );
        } catch (Exception $e) {
            DB::rollBack();
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