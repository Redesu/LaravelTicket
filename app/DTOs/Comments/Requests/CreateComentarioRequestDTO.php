<?php
namespace App\DTOs\Comments\Requests;

use App\Http\Requests\AdicionarComentariosRequest;
use Auth;

class CreateComentarioRequestDTO
{
    public function __construct(
        private ?int $usuario_id,
        private ?string $descricao,
        private ?string $tipo,
        private ?string $changes,
        private ?array $anexos = null,
    ) {
    }

    public static function fromRequest(AdicionarComentariosRequest $request): self
    {
        $validatedData = $request->validated();
        return new CreateComentarioRequestDTO(
            usuario_id: Auth::id(),
            descricao: $validatedData['descricao'],
            tipo: $validatedData['tipo'] ?? 'comment',
            changes: $validatedData['changes'] ?? null,
            anexos: $validatedData['anexos'] ?? null,
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

    public function getChanges(): ?string
    {
        return $this->changes;
    }

    public function getAnexos(): ?array
    {
        return $this->anexos;
    }

}