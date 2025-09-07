<?php
namespace App\DTOs\DepartamentoManagement\Data;

class DepartamentoDataRableRowDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $nome,
        public readonly ?string $descricao,
        public readonly ?string $criado_em
    ) {
    }

    public static function fromDataBaseRow(object $row): self
    {
        return new self(
            id: $row->id,
            nome: $row->nome,
            descricao: $row->descricao,
            criado_em: $row->criado_em ? $row->criado_em : null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'criado_em' => $this->criado_em
        ];
    }
}