<?php
namespace App\DTOs\CategoriaManagement\Requests;

use App\Http\Requests\DeleteCategoriaRequest;

class DeleteCategoriaRequestDTO
{
    public function __construct(
        public readonly int $id
    ) {
    }

    public function create(int $id): self
    {
        return new self($id);
    }

    public static function fromRequest(DeleteCategoriaRequest $request): self
    {
        $validatedData = $request->validated();
        return new self($validatedData['id']);
    }

    public function getCategoriaId(): int
    {
        return $this->id;
    }
}