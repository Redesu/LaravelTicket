<?php
namespace App\Services;

use App\DTOs\ChamadoDataTableRowDTO;
use App\DTOs\DataTableRequestDTO;
use App\DTOs\DataTableResponseDTO;
use DB;

class ChamadoDataTableService
{
    public function getChamadosFromDataTable(DataTableRequestDTO $request): DataTableResponseDTO
    {
        $query = $this->buildBaseQuery();

        $this->applyStatusFilter($query, $request->getFilters());
        $this->applySearch($query, $request->getSearchValue());
        $this->applyFilters($query, $request->getFilters());

        $recordsFiltered = $this->getFilteredCount($query);
        $recordsTotal = $this->getTotalCount();

        $this->applyPagination($query, $request->getStart(), $request->getLength());

        $chamados = $query->get();

        $chamadoDTOs = $chamados->map(function ($chamado) {
            return ChamadoDataTableRowDTO::fromDataBaseRow($chamado)->toArray();
        })->toArray();

        return DataTableResponseDTO::create(
            $request->getDraw(),
            $recordsTotal,
            $recordsFiltered,
            $chamadoDTOs
        );
    }

    private function buildBaseQuery()
    {
        return DB::table('chamados as c')
            ->leftJoin('categorias as cat', 'c.categoria_id', '=', 'cat.id')
            ->leftJoin('departamentos as dep', 'c.departamento_id', '=', 'dep.id')
            ->select([
                'c.id',
                'c.titulo',
                'c.descricao',
                'c.status',
                'c.prioridade',
                'cat.nome as categoria',
                'dep.nome as departamento',
                'c.user_id as usuario_id',
                'c.created_at as data_abertura'
            ]);
    }

    private function applyStatusFilter($query, array $filters): void
    {
        if (empty($filters['status']) || $filters['status'] !== 'Finalizado') {
            $query->where('c.status', '!=', 'Finalizado');
        }
    }

    private function applySearch($query, string $searchValue): void
    {
        if (empty($searchValue)) {
            return;
        }

        $query->where(function ($q) use ($searchValue) {
            $q->where('c.status', 'like', "%{$searchValue}%")
                ->orWhere('c.prioridade', 'like', "%{$searchValue}%")
                ->orWhere('dep.nome', 'like', "%{$searchValue}%")
                ->orWhere('cat.nome', 'like', "%{$searchValue}%")
                ->orWhere('c.user_id', 'like', "%{$searchValue}%")
                ->orWhere('c.titulo', 'like', "%{$searchValue}%");
        });
    }

    private function applyFilters($query, array $filters): void
    {
        foreach ($filters as $key => $value) {
            if (empty($value) || $key === 'status') {
                continue;
            }

            switch ($key) {
                case 'status':
                    $query->where('c.status', $filters['status']);
                    break;
                case 'prioridade':
                    $query->where('c.prioridade', $filters['prioridade']);
                    break;
                case 'user_id':
                    $query->where('c.user_id', $filters['user_id']);
                    break;
                case 'departamento':
                    $query->where('c.departamento_id', $filters['departamento']);
                    break;
                case 'categoria':
                    $query->where('c.categoria_id', $filters['categoria']);
                    break;
                case 'created_at_inicio':
                    $query->whereDate('c.created_at', '>=', $filters['created_at_inicio']);
                    break;
                case 'created_at_fim':
                    $query->where('c.created_at', '<=', $filters['created_at_fim']);
                    break;
                case 'updated_at_inicio':
                    $query->whereDate('c.updated_at', '>=', $filters['updated_at_inicio']);
                    break;
                case 'updated_at_fim':
                    $query->where('c.updated_at', '<=', $filters['updated_at_fim']);
                    break;
            }
        }
    }

    private function getFilteredCount($query): int
    {
        return (clone $query)->count();
    }

    private function getTotalCount(): int
    {
        return DB::table('chamados')->count();
    }

    private function applyPagination($query, int $start, int $length): void
    {
        $query->offset($start)->limit($length);
    }

}