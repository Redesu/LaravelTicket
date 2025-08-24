<?php
namespace App\DTOs;

use Symfony\Component\HttpFoundation\JsonResponse;

class DataTableResponseDTO
{
    public function __construct(
        public readonly string $draw,
        public readonly int $recordsTotal,
        public readonly int $recordsFiltered,
        public readonly array $data
    ) {
    }

    public static function create(
        string $draw,
        int $recordsTotal,
        int $recordsFiltered,
        array $data
    ): self {
        return new self($draw, $recordsTotal, $recordsFiltered, $data);
    }

    public function toArray(): array
    {
        return [
            'draw' => (int) $this->draw,
            'recordsTotal' => $this->recordsTotal,
            'recordsFiltered' => $this->recordsFiltered,
            'data' => $this->data
        ];
    }

    public function toJsonResponse(): JsonResponse
    {
        return response()->json($this->toArray());
    }
}