<?php
namespace App\DTOs;

use App\Models\Chamado;

class ChamadoOriginalDataDTO
{
    public function __construct(
        private string $titulo,
        private string $descricao,
        private string $prioridade,
        private string $status,
        private int $categoria_id,
        private int $departamento_id,
        private int $user_id,
        private string $user_name
    ) {
    }

    public function toArray(): array
    {
        return [
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'prioridade' => $this->prioridade,
            'status' => $this->status,
            'categoria_id' => $this->categoria_id,
            'departamento_id' => $this->departamento_id,
            'user_id' => $this->user_id
        ];
    }

    public static function fromChamado(Chamado $chamado): self
    {
        return new self(
            titulo: $chamado->titulo,
            descricao: $chamado->descricao,
            prioridade: $chamado->prioridade,
            status: $chamado->status,
            categoria_id: $chamado->categoria->id,
            departamento_id: $chamado->departamento->id,
            user_id: $chamado->user_id,
            user_name: $chamado->usuario->name
        );
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getUserName(): string
    {
        return $this->user_name;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getPrioridade(): string
    {
        return $this->prioridade;
    }

    public function getCategoriaId(): int
    {
        return $this->categoria_id;
    }

    public function getDepartamentoId(): int
    {
        return $this->departamento_id;
    }
}