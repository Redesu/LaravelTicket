<?php
namespace App\DTOs;

use Illuminate\Http\JsonResponse;

class AddComentarioResponseDTO
{
    public function __construct(
        public readonly bool $success,
        public readonly ?array $comentario = null,
        public readonly ?array $usuario = null,
        public readonly ?string $created_at = null,
        public readonly ?string $descricao = null,
        public readonly ?string $message = null,
        public readonly ?string $error = null,
    ) {
    }

    public static function success(array $comentario, array $usuario, string $created_at, string $descricao): self
    {
        return new self(
            success: true,
            comentario: $comentario,
            usuario: $usuario,
            created_at: $created_at,
            descricao: $descricao
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
            'usuario' => $this->usuario,
            'created_at' => $this->created_at,
            'descricao' => $this->descricao,
            'message' => $this->message,
            'error' => $this->error,
        ];
    }

    public function toJsonResponse(int $statusCode = null): JsonResponse
    {
        $code = $statusCode ?? ($this->success ? 201 : 500);
        return response()->json($this->toArray(), $code);
    }
}
