<?php
namespace App\DTOs\Solutions\Requests;

use App\Http\Requests\AdicionarSolucaoRequest;
use Auth;

class CreateSolucaoRequestDTO
{
    public function __construct(
        private ?int $usuario_id,
        private ?string $descricao,
        private ?string $tipo,
    ) {
    }

    public static function fromRequest(AdicionarSolucaoRequest $request): self
    {
        $validatedData = $request->validated();
        return new CreateSolucaoRequestDTO(
            usuario_id: Auth::id(),
            descricao: $validatedData['descricao'],
            tipo: 'solution'
        );
    }

    public function getUsuarioId(): ?int
    {
        return $this->usuario_id;
    }

    public function getDesc(): ?string
    {
        return $this->descricao;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

}