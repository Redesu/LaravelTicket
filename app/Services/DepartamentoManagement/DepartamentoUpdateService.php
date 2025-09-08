<?php
namespace App\Services\DepartamentoManagement;

use App\DTOs\DepartamentoManagement\Requests\UpdateDepartamentoRequestDTO;
use App\DTOs\DepartamentoManagement\Responses\DepartamentoResponseDTO;
use App\Models\Departamento;
use Log;

class DepartamentoUpdateService
{
    public function updateDepartamento(UpdateDepartamentoRequestDTO $updateDTO, int $id): DepartamentoResponseDTO
    {
        try {
            $departamento = Departamento::findOrFail($id);
            $departamento->update($updateDTO->toArray());

            return DepartamentoResponseDTO::success(
                data: $departamento->toArray(),
                message: 'Departamento atualizado com sucesso.'
            );
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar departamento: ' . $e->getMessage());
            return DepartamentoResponseDTO::error(
                message: 'Erro ao atualizar departamento.',
                error: 'Ocorreu um erro ao atualizar o departamento. Por favor, tente novamente.'
            );
        }
    }
}