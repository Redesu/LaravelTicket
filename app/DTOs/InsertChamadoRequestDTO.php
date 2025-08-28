<?php
namespace App\DTOs;

use App\Http\Requests\StoreChamadoRequest;

class InsertChamadoRequestDTO
{
    public function __construct(
        public readonly string $titulo,
        public readonly string $descricao,
        public readonly int $user_id,
        public readonly string $prioridade,
        public readonly int $categoria_id,
        public readonly int $departamento_id
    ) {
    }

    /**
     * Create DTO from validated insert request data
     */
    public static function fromRequest(StoreChamadoRequest $request): self
    {
        $validatedData = $request->validated();
        return new self(
            titulo: $validatedData['titulo'],
            descricao: $validatedData['descricao'],
            user_id: $validatedData['user_id'],
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
            'prioridade' => $this->prioridade,
            'categoria_id' => $this->categoria_id,
            'departamento_id' => $this->departamento_id,
        ];
    }

    /**
     * Getters
     */

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getPrioridade(): string
    {
        return $this->prioridade;
    }

    public function getCategoriaId(): int
    {
        return $this->categoria_id;
    }

    public function getDepartamentoId(): int
    {
        return $this->departamento_id;
    }
}