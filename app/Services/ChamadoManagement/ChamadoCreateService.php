<?php

namespace App\Services\ChamadoManagement;

use App\DTOs\ChamadoManagement\Requests\CreateChamadoRequestDTO;
use App\DTOs\ChamadoManagement\Responses\CreateChamadoResponseDTO;
use App\Models\Chamado;
use App\Services\Anexos\ProcessAnexosService;
use Carbon\Exceptions\InvalidTypeException;
use DB;
use Dotenv\Exception\InvalidFileException;
use Exception;
use Log;

class ChamadoCreateService
{

    public function __construct(
        private ProcessAnexosService $processAnexosService
    ) {
    }
    public function criarChamado(CreateChamadoRequestDTO $DTO): CreateChamadoResponseDTO
    {
        DB::beginTransaction();
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

            if ($DTO->getAnexos()) {
                $this->processAnexosService->processAnexos($DTO->getAnexos(), $chamado);
            }

            DB::commit();

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
        } catch (InvalidFileException $e) {
            DB::rollBack();
            Log::warning('Arquivo inválido ao criar chamado: ' . $e->getMessage());
            return CreateChamadoResponseDTO::error(
                message: 'Arquivo Inválido',
                error: $e->getMessage()
            );
        } catch (InvalidTypeException $e) {
            DB::rollBack();
            Log::warning('Tipo de arquivo inválido ao criar chamado: ' . $e->getMessage());
            return CreateChamadoResponseDTO::error(
                message: 'Tipo de arquivo Inválido ao criar chamado',
                error: $e->getMessage()
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error ao criar chamado: ' . $e->getMessage());
            return CreateChamadoResponseDTO::error(
                message: 'Erro ao criar chamado',
                error: 'Ocorreu um erro inesperado. Por favor, tente novamente.'
            );
        }
    }
}