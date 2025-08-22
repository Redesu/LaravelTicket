<?php
namespace App\DTOs;

use App\Http\Requests\UpdateChamadoRequest;

class UpdateChamadoDTO
{
    public function __construct(
        private ?string $titulo = null,
        private ?string $descricao = null,
        private ?string $prioridade = null,
        private ?string $status = null,
        private ?int $categoria_id = null,
        private ?int $departamento_id = null,
        private ?int $user_id = null
    ) {
    }

    /**
     * Create DTO from validated insert request data
     */
    public static function fromRequest(UpdateChamadoRequest $request): self
    {
        $validatedData = $request->validated();
        return new UpdateChamadoDTO(
            titulo: $validatedData['titulo'],
            descricao: $validatedData['descricao'],
            user_id: $validatedData['user_id'],
            status: $validatedData['status'],
            prioridade: $validatedData['prioridade'],
            categoria_id: $validatedData['categoria_id'],
            departamento_id: $validatedData['departamento_id']
        );
    }

    /**
     * Convert DTO to array
     */
    public function toArray(): array
    {
        return [
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'prioridade' => $this->prioridade,
            'categoria_id' => $this->categoria_id,
            'departamento_id' => $this->departamento_id,
        ];
    }

    /**
     * Getters
     */

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getPrioridade(): ?string
    {
        return $this->prioridade;
    }

    public function getCategoriaId(): ?int
    {
        return $this->categoria_id;
    }

    public function getDepartamentoId(): ?int
    {
        return $this->departamento_id;
    }

}