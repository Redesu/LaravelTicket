<?php

namespace App\DTOs\CategoriaManagement\Requests;

use App\Http\Requests\UpdateCategoriaRequest;

class UpdateCategoriaRequestDTO
{
    public function __construct(
        private ?string $nome = null
    ) {
    }

    public static function fromRequest(UpdateCategoriaRequest $request): self
    {
        $validatedData = $request->validated();
        return new UpdateCategoriaRequestDTO(
            nome: $validatedData['nome']
        );
    }

    public function toArray(): array
    {
        return [
            'nome' => $this->nome,
        ];
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }
}