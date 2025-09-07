<?php
namespace App\DTOs\DepartamentoManagement\Requests;

use App\Http\Requests\StoreDepartamentoRequest;

class CreateDepartamentoRequestDTO
{
    public function __construct(
        public readonly string $nome,
        public readonly string $descricao
    ) {
    }

    public static function fromRequest(StoreDepartamentoRequest $request): self
    {
        $validatedData = $request->validated();
        return new self(
            nome: $validatedData['nome'],
            descricao: $validatedData['descricao']
        );
    }

    public function toArray(): array
    {
        return [
            'nome' => $this->nome,
            'descricao' => $this->descricao
        ];
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }
}