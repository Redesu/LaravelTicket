<?php
namespace App\DTOs\DepartamentoManagement\Requests;

use App\Http\Requests\UpdateDepartamentoRequest;

class UpdateDepartamentoRequestDTO
{
    public function __construct(
        public readonly ?string $nome,
        public readonly ?string $descricao
    ) {
    }

    public static function fromRequest(UpdateDepartamentoRequest $request): self
    {
        $validatedData = $request->validated();
        return new self(
            nome: $validatedData['nome'] ?? null,
            descricao: $validatedData['descricao'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'nome' => $this->nome,
            'descricao' => $this->descricao,
        ];
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }
}