<?php
namespace App\Services\CategoriaManagement;

use App\DTOs\CategoriaManagement\Requests\CreateCategoriaRequestDTO;
use App\DTOs\CategoriaManagement\Responses\CategoriaResponseDTO;
use App\Models\Categoria;
use Log;

class CategoriaCreateService
{
    public function criarCategoria(CreateCategoriaRequestDTO $DTO): CategoriaResponseDTO
    {
        try {
            $categoria = Categoria::create([
                'nome' => $DTO->getNome()
            ]);

            return CategoriaResponseDTO::success(
                data: $categoria->toArray(),
                message: 'Categoria criada com sucesso.'
            );
        } catch (\Exception $e) {
            Log::error('Error ao criar categoria: ' . $e->getMessage());
            return CategoriaResponseDTO::error(
                message: 'Erro ao criar categoria.',
                error: 'Ocorreu um erro ao criar a categoria. Por favor, tente novamente.'
            );
        }
    }
}