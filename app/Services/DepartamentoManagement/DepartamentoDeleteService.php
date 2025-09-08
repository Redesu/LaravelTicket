<?php
namespace App\Services\DepartamentoManagement;

use App\DTOs\DepartamentoManagement\Requests\DeleteDepartamentoRequestDTO;
use App\DTOs\DepartamentoManagement\Responses\DepartamentoResponseDTO;
use DB;
use Log;

class DepartamentoDeleteService
{
    public function deleteDepartamento(DeleteDepartamentoRequestDTO $request): DepartamentoResponseDTO
    {
        try {
            $departamento = $this->findDepartamento($request->getDepartamentoId());

            if (!$departamento) {
                return DepartamentoResponseDTO::error(
                    message: 'Departamento não encontrado.',
                    error: 'O departamento especificado não foi encontrado ou já foi excluído.'
                );
            }

            $this->performSoftDelete($request->getDepartamentoId());

            return DepartamentoResponseDTO::success(
                data: ['departamento_id' => $request->getDepartamentoId()],
                message: 'Departamento excluído com sucesso.'
            );
        } catch (\Exception $e) {
            Log::error('Erro ao excluir departamento: ' . $e->getMessage());
            return DepartamentoResponseDTO::error(
                message: 'Erro ao excluir departamento.',
                error: 'Ocorreu um erro ao excluir o departamento. Por favor, tente novamente.'
            );
        }
    }

    private function findDepartamento(int $departamentoId): ?object
    {
        return DB::table('departamentos')
            ->where('id', $departamentoId)
            ->whereNull('deleted_at')
            ->first();
    }

    private function performSoftDelete(int $departamentoId): bool
    {
        $affectedRows = DB::table('departamentos')
            ->where('id', $departamentoId)
            ->whereNull('deleted_at')
            ->update([
                'updated_at' => now(),
                'deleted_at' => now()
            ]);

        return $affectedRows > 0;
    }
}