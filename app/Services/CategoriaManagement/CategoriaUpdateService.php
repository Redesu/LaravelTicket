<?php
namespace App\Services\CategoriaManagement;

use App\DTOs\CategoriaManagement\Requests\UpdateCategoriaRequestDTO;
use App\DTOs\CategoriaManagement\Responses\CategoriaResponseDTO;
use App\Models\Categoria;
use Log;

class CategoriaUpdateService
{
    public function updateCategoria(UpdateCategoriaRequestDTO $updateDTO, int $id): CategoriaResponseDTO
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->update($updateDTO->toArray());

            return CategoriaResponseDTO::success(
                data: $categoria->toArray(),
                message: 'Categoria atualizada com sucesso.'
            );
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar categoria: ' . $e->getMessage());
            return CategoriaResponseDTO::error(
                message: 'Erro ao atualizar categoria.',
                error: 'Ocorreu um erro ao atualizar a categoria. Por favor, tente novamente.'
            );
        }

    }
}