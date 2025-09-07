<?php
namespace App\DTOs\DepartamentoManagement\Requests;

use App\Http\Requests\DeleteDepartamentoRequest;

class DeleteDepartamentoRequestDTO
{
    public function __construct(
        public readonly int $id
    ) {
    }

    public function create(int $id): self
    {
        return new self($id);
    }

    public static function fromRequest(DeleteDepartamentoRequest $request): self
    {
        $validatedData = $request->validated();
        return new self($validatedData['id']);
    }

    public function getDepartamentoId(): int
    {
        return $this->id;
    }
}