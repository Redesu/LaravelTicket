<?php
namespace App\DTOs;

use App\Models\Chamado;
use Illuminate\Http\JsonResponse;

class ChamadoUpdateResultDTO
{
    public function __construct(
        private bool $success,
        private string $message,
        private array $changes = [],
        private ?Chamado $chamado = null,
        private array $newData = []
    ) {
    }

    public function toJsonResponse(): JsonResponse
    {
        $response = [
            'success' => $this->success,
            'message' => $this->message
        ];

        if ($this->success) {
            $response['newData'] = $this->newData;
            $response['changes'] = $this->changes;
            $response['data'] = $this->chamado;
            return response()->json($response, 200);
        }

        return response()->json($response, 400);
    }






    public function isSuccess(): bool
    {
        return $this->success;
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