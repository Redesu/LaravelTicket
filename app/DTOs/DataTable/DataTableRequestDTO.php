<?php
namespace App\DTOs\DataTable;

class DataTableRequestDTO
{
    public function __construct(
        public readonly string $draw,
        public readonly int $start,
        public readonly int $length,
        public readonly ?string $searchValue,
    ) {
    }

    public static function fromRequest(array $requestData): self
    {
        return new self(
            draw: $requestData['draw'] ?? '1',
            start: $requestData['start'] ?? 0,
            length: $requestData['length'] ?? 10,
            searchValue: $requestData['search']['value'] ?? '',
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

}