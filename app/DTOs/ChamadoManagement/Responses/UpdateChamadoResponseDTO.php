<?php
namespace App\DTOs\ChamadoManagement\Responses;

use App\Models\Chamado;
use Illuminate\Http\JsonResponse;

class UpdateChamadoResponseDTO
{
    public function __construct(
        private ?bool $success,
        private ?string $message,
        private ?array $changes = [],
        private ?Chamado $chamado = null,
        private ?array $newData = [],
        private ?string $error = null
    ) {
    }

    public static function success(Chamado $chamado, string $message, array $changes = [], array $newData = []): self
    {
        return new self(
            success: true,
            message: $message,
            changes: $changes,
            chamado: $chamado,
            newData: $newData
        );
    }

    public static function error(string $message, string $error): self
    {
        return new self(
            success: false,
            message: $message,
            error: $error
        );
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'changes' => $this->changes,
            'chamado' => $this->chamado,
            'newData' => $this->newData,
            'error' => $this->error
        ];
    }

    public function toJsonResponse(int $statusCode = null): JsonResponse
    {
        $statusCode = $statusCode ?? ($this->success ? 201 : 500);
        return response()->json($this->toArray(), $statusCode);
    }


    public function getMessage(): string
    {
        return $this->message;
    }

    public function getChanges(): array
    {
        return $this->changes;
    }

    public function getChamado(): ?Chamado
    {
        return $this->chamado;
    }

    public function getNewData(): array
    {
        return $this->newData;
    }
}