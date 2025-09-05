<?php
namespace App\DTOs\CategoriaManagement\Data;

class CategoriaDataTableRowDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $nome,
        public readonly ?string $criado_em
    ) {
    }

    public static function fromDataBaseRow(object $row): self
    {
        return new self(
            id: $row->id,
            nome: $row->nome,
            criado_em: $row->criado_em ? $row->criado_em : null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'criado_em' => $this->criado_em
        ];
    }

}