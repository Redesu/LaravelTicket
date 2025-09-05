<?php
namespace App\DTOs\CategoriaManagement\Requests;

use App\Http\Requests\StoreCategoriaRequest;

class CreateCategoriaRequestDTO
{
    public function __construct(
        public readonly string $nome
    ) {
    }

    public static function fromRequest(StoreCategoriaRequest $request): self
    {
        $validatedData = $request->validated();
        return new self(
            nome: $validatedData['nome']
        );
    }

    public function toArray(): array
    {
        return [
            'nome' => $this->nome
        ];
    }

    public function getNome(): string
    {
        return $this->nome;

    }
}