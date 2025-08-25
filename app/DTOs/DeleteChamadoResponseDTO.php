<?php
namespace App\DTOs;

use Illuminate\Http\JsonResponse;

class DeleteChamadoResponseDTO
{
    private bool $success;
    private string $message;
    private ?int $chamadoId;
    private ?array $errors;

    public function __construct(
        bool $success,
        string $message,
        ?int $chamadoId = null,
        ?array $errors = null
    ) {
        $this->success = $success;
        $this->message = $message;
        $this->chamadoId = $chamadoId;
        $this->errors = $errors;
    }

    public static function success(int $chamadoId, string $message = 'Chamado deletado com sucesso.'): self
    {
        return new self(true, $message, $chamadoId);
    }

    public static function error(string $message, ?array $errors = null): self
    {
        return new self(false, $message, null, $errors);
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getChamadoId(): ?int
    {
        return $this->chamadoId;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function toArray(): array
    {
        $response = [
            'success' => $this->success,
            'message' => $this->message,
        ];

        if ($this->chamadoId !== null) {
            $response['chamado_id'] = $this->chamadoId;
        }

        if ($this->errors !== null) {
            $response['errors'] = $this->errors;
        }

        return $response;
    }

    public function toJsonResponse(int $status = 200): JsonResponse
    {
        return response()->json($this->toArray(), $status);
    }
}