<?php
namespace App\DTOs;

use App\Http\Requests\DataTableChamadoRequest;


class DataTableRequestDTO
{
    public function __construct(
        public readonly string $draw,
        public readonly int $start,
        public readonly int $length,
        public readonly ?string $searchValue,
        public readonly ?array $filters,
        public readonly ?string $status = null,
        public readonly ?string $prioridade = null,
        public readonly ?int $usuario_id = null,
        public readonly ?string $departamento = null,
        public readonly ?string $categoria = null,
        public readonly ?string $created_at_inicio = null,
        public readonly ?string $created_at_fim = null,
        public readonly ?string $updated_at_inicio = null,
        public readonly ?string $updated_at_fim = null
    ) {
    }

    public static function fromRequest(array $requestData): self
    {
        return new self(
            draw: $requestData['draw'] ?? '1',
            start: $requestData['start'] ?? 0,
            length: $requestData['length'] ?? 10,
            searchValue: $requestData['search']['value'] ?? '',
            filters: $requestData['filters'] ?? [],
            status: $requestData['status'] ?? null,
            prioridade: $requestData['prioridade'] ?? null,
            usuario_id: $requestData['usuario_id'] ?? null,
            departamento: $requestData['departamento'] ?? null,
            categoria: $requestData['categoria'] ?? null,
            created_at_inicio: $requestData['created_at_inicio'] ?? null,
            created_at_fim: $requestData['created_at_fim'] ?? null,
            updated_at_inicio: $requestData['updated_at_inicio'] ?? null,
            updated_at_fim: $requestData['updated_at_fim'] ?? null,
        );
    }


    public function getDraw(): string
    {
        return $this->draw;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getSearchValue(): ?string
    {
        return $this->searchValue;
    }

    public function getFilters(): ?array
    {
        return $this->filters;

    }
}