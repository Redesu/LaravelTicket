<?php
namespace App\Services\Solutions;

use App\DTOs\Solutions\Requests\CreateSolucaoRequestDTO;
use App\DTOs\Solutions\Responses\CreateSolucaoResponseDTO;
use App\Models\Chamado;
use App\Models\ChamadoComentario;
use App\Services\Anexos\ProcessAnexosService;
use Carbon\Exceptions\InvalidTypeException;
use DB;
use Dotenv\Exception\InvalidFileException;
use Log;


class AddSolucaoService
{
    public function __construct(
        private ProcessAnexosService $processAnexosService
    ) {
    }
    public function addSolucao(CreateSolucaoRequestDTO $request, int $id): CreateSolucaoResponseDTO
    {
        DB::beginTransaction();
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

            if ($request->getAnexos()) {
                $this->processAnexosService->processAnexos($request->getAnexos(), $solucao);
            }

            $chamado->update(['status' => 'Finalizado']);
            $solucao->load('usuario');

            DB::commit();

            return CreateSolucaoResponseDTO::success(
                comentario: $this->formatSolucaoData($solucao),
                message: 'Solução adicionada e chamado marcado como resolvido.',
            );
        } catch (InvalidFileException $e) {
            DB::rollBack();
            Log::warning('Arquivo inválido ao criar chamado: ' . $e->getMessage());
            return CreateSolucaoResponseDTO::error(
                message: 'Arquivo Inválido',
                error: $e->getMessage()
            );
        } catch (InvalidTypeException $e) {
            DB::rollBack();
            Log::warning('Tipo de arquivo inválido ao criar chamado: ' . $e->getMessage());
            return CreateSolucaoResponseDTO::error(
                message: 'Tipo de arquivo Inválido ao criar chamado',
                error: $e->getMessage()
            );
        } catch (\Exception $e) {
            DB::rollBack();
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