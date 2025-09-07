<?php
namespace App\DTOs\DepartamentoManagement\Responses;

class DepartamentoResponseDTO
{
    public function __construct(
        public readonly bool $success,
        public readonly ?array $data = null,
        public readonly ?string $message = null,
        public readonly ?string $error = null
    ) {
    }

    public static function success(array $data, string $message): self
    {
        return new self(success: true, data: $data, message: $message);
    }

    public static function error(string $message, string $error): self
    {
        return new self(success: false, message: $message, error: $error);
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'data' => $this->data,
            'message' => $this->message,
            'error' => $this->error,
        ];
    }

    public function toJsonResponse(int $statusCode = null): JsonResponse
    {
        $statusCode = $statusCode ?? ($this->success ? 200 : 500);
        return response()->json($this->toArray(), $statusCode);
    }
}