<?php
namespace App\DTOs\Solutions\Responses;

use Illuminate\Http\JsonResponse;

class CreateSolucaoResponseDTO
{
    public function __construct(
        public readonly bool $success,
        public readonly ?array $comentario = null,
        public readonly ?string $message = null,
        public readonly ?string $error = null
    ) {
    }

    public static function success(array $comentario, string $message): self
    {
        return new self(
            success: true,
            comentario: $comentario,
            message: $message
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
            'comentario' => $this->comentario,
            'message' => $this->message,
            'error' => $this->error,
        ];
    }

    public function toJsonResponse(int $statusCode = null): JsonResponse
    {
        $statusCode = $statusCode ?? ($this->success ? 201 : 500);
        return response()->json($this->toArray(), $statusCode);
    }
}