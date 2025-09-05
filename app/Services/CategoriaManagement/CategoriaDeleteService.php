<?php
namespace App\Services\CategoriaManagement;

use App\DTOs\CategoriaManagement\Requests\DeleteCategoriaRequestDTO;
use App\DTOs\CategoriaManagement\Responses\CategoriaResponseDTO;
use DB;

class CategoriaDeleteService
{

    public function deleteCategoria(DeleteCategoriaRequestDTO $request): CategoriaResponseDTO
    {
        try {
            $categoria = $this->findCategoria($request->getCategoriaId());

            if (!$categoria) {
                return CategoriaResponseDTO::error('Categoria não encontrada', 'A categoria que você está tentando excluir não existe ou já foi excluída.');
            }

            $deleted = $this->performSoftDelete($request->getCategoriaId());

            if (!$deleted) {
                return CategoriaResponseDTO::error('Erro ao excluir a categoria', 'Ocorreu um erro ao tentar excluir a categoria. Por favor, tente novamente.');
            }

            return CategoriaResponseDTO::success(
                data: ['categoria_id' => $request->getCategoriaId()],
                message: 'Categoria excluída com sucesso.'
            );
        } catch (\Exception $e) {
            return CategoriaResponseDTO::error('Erro ao excluir a categoria', $e->getMessage());
        }
    }

    private function findCategoria(int $categoriaId): ?object
    {
        return DB::table('categorias')
            ->where('id', $categoriaId)
            ->whereNull('deleted_at')
            ->first();
    }

    private function performSoftDelete(int $categoriaId): bool
    {
        $affectedRows = DB::table('categorias')
            ->where('id', $categoriaId)
            ->whereNull('deleted_at')
            ->update([
                'updated_at' => now(),
                'deleted_at' => now()
            ]);

        return $affectedRows > 0;
    }
}