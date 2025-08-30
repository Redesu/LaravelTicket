<?php
namespace App\DTOs\ChamadoManagement\Requests;

use App\Http\Requests\UpdateChamadoRequest;

class UpdateChamadoRequestDTO
{
    public function __construct(
        private ?string $titulo = null,
        private ?string $descricao = null,
        private ?string $prioridade = null,
        private ?string $status = null,
        private ?string $categoria_nome = null,
        private ?string $departamento_nome = null,
        private ?int $user_id = null
    ) {
    }

    /**
     * Create DTO from validated insert request data
     */
    public static function fromRequest(UpdateChamadoRequest $request): self
    {
        $validatedData = $request->validated();
        return new UpdateChamadoRequestDTO(
            titulo: $validatedData['titulo'],
            descricao: $validatedData['descricao'],
            user_id: $validatedData['user_id'],
            status: $validatedData['status'],
            prioridade: $validatedData['prioridade'],
            categoria_nome: $validatedData['categoria_nome'],
            departamento_nome: $validatedData['departamento_nome']
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
            'categoria_nome' => $this->categoria_nome,
            'departamento_nome' => $this->departamento_nome,
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

    public function getCategoria(): ?int
    {
        return $this->categoria_nome;
    }

    public function getDepartamento(): ?int
    {
        return $this->departamento_nome;
    }

}