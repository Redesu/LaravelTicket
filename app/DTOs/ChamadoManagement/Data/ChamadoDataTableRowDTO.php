<?php
namespace App\DTOs\ChamadoManagement\Data;

class ChamadoDataTableRowDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $titulo,
        public readonly string $descricao,
        public readonly string $status,
        public readonly string $prioridade,
        public readonly ?string $categoria,
        public readonly ?string $departamento,
        public readonly ?int $usuario_id,
        public readonly ?string $data_abertura
    ) {
    }

    public static function fromDataBaseRow(object $row): self
    {
        return new self(
            id: $row->id,
            titulo: $row->titulo,
            descricao: $row->descricao,
            status: $row->status,
            prioridade: $row->prioridade,
            categoria: $row->categoria,
            departamento: $row->departamento,
            usuario_id: $row->usuario_id,
            data_abertura: $row->data_abertura ? $row->data_abertura : null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'status' => $this->status,
            'prioridade' => $this->prioridade,
            'categoria' => $this->categoria,
            'departamento' => $this->departamento,
            'usuario_id' => $this->usuario_id,
            'data_abertura' => $this->data_abertura
        ];
    }
}