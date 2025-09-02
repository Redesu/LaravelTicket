<?php
namespace App\Services\ChamadoManagement;

use App\DTOs\ChamadoManagement\Requests\DeleteChamadoRequestDTO;
use App\DTOs\ChamadoManagement\Responses\DeleteChamadoResponseDTO;
use DB;
use Log;

class ChamadoDeleteService
{

    public function deleteChamado(DeleteChamadoRequestDTO $request): DeleteChamadoResponseDTO
    {
        try {
            $chamado = $this->findChamado($request->getChamadoId());

            if (!$chamado) {
                return DeleteChamadoResponseDTO::error('Chamado não encontrado');
            }

            if (!$this->isUserAuthorized($chamado, $request->getUserId())) {
                return DeleteChamadoResponseDTO::error('Você não tem permissão para excluir este chamado');
            }

            $deleted = $this->performSoftDelete($request->getChamadoId(), $chamado->created_by);

            if (!$deleted) {
                return DeleteChamadoResponseDTO::error('Erro ao excluir o chamado');
            }

            return DeleteChamadoResponseDTO::success($request->getChamadoId());
        } catch (\Exception $e) {
            Log::error('Error ao excluir chamado: ' . $e->getMessage());
            return DeleteChamadoResponseDTO::error($e->getMessage());
        }
    }


    public function findChamado(int $chamadoId): ?object
    {
        return DB::table('chamados')
            ->where('id', $chamadoId)
            ->whereNull('deleted_at')
            ->first();
    }

    public function isUserAuthorized(object $chamado, int $user_id): bool
    {
        return $chamado->created_by === $user_id;
    }

    public function performSoftDelete(int $chamadoId, int $created_by): bool
    {
        $affectedRows = DB::table('chamados')
            ->where('id', $chamadoId)
            ->where('created_by', $created_by)
            ->whereNull('deleted_at')
            ->update([
                'updated_at' => now(),
                'deleted_at' => now()
            ]);

        return (int) $affectedRows > 0;
    }
}
