<?php
namespace App\Services\DepartamentoManagement;

use App\DTOs\DepartamentoManagement\Requests\CreateDepartamentoRequestDTO;
use App\DTOs\DepartamentoManagement\Responses\DepartamentoResponseDTO;
use App\Models\Departamento;
use Log;

class DepartamentoCreateService
{
    public function criarDepartamento(CreateDepartamentoRequestDTO $DTO): DepartamentoResponseDTO
    {
        try {
            $departamento = Departamento::create([
                'nome' => $DTO->getNome(),
                'descricao' => $DTO->getDescricao()
            ]);

            return DepartamentoResponseDTO::success(
                data: $departamento->toArray(),
                message: 'Departamento criado com sucesso.'
            );
        } catch (\Exception $e) {
            Log::error('Error ao criar departamento: ' . $e->getMessage());
            return DepartamentoResponseDTO::error(
                message: 'Erro ao criar departamento.',
                error: 'Ocorreu um erro ao criar o departamento. Por favor, tente novamente.'
            );
        }
    }

}