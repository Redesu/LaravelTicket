<?php
namespace App\Services\ChamadoManagement;

use App\DTOs\ChamadoManagement\Data\ChamadoDataTableRowDTO;
use App\DTOs\DataTable\DataTableRequestDTO;
use App\DTOs\DataTable\DataTableResponseDTO;
use DB;

class ChamadoDataTableService
{
    public function getChamadosFromDataTable(DataTableRequestDTO $request): DataTableResponseDTO
    {
        $query = $this->buildBaseQuery();

        $this->applyFilters($query, $request);
        $this->applySearch($query, $request->getSearchValue());

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
            ->whereNull('c.deleted_at')
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

    private function applyFilters($query, DataTableRequestDTO $request): void
    {
        if (!empty($request->getStatus())) {
            $query->where('c.status', $request->getStatus());
        } else {
            $query->where('c.status', '!=', 'Finalizado');
        }

        if (!empty($request->getPrioridade())) {
            $query->where('c.prioridade', $request->getPrioridade());
        }

        if (!empty($request->getUsuarioId())) {
            $query->where('c.user_id', $request->getUsuarioId());
        }

        if (!empty($request->getDepartamento())) {
            $query->where('c.departamento_id', $request->getDepartamento());
        }

        if (!empty($request->getCategoria())) {
            $query->where('c.categoria_id', $request->getCategoria());
        }

        if (!empty($request->getCreatedAtInicio())) {
            $query->whereDate('c.created_at', '>=', $request->getCreatedAtInicio());
        }

        if (!empty($request->getCreatedAtFim())) {
            $query->whereDate('c.created_at', '<=', $request->getCreatedAtFim());
        }

        if (!empty($request->getUpdatedAtInicio())) {
            $query->whereDate('c.updated_at', '>=', $request->getUpdatedAtInicio());
        }

        if (!empty($request->getUpdatedAtFim())) {
            $query->whereDate('c.updated_at', '<=', $request->getUpdatedAtFim());
        }
    }

    private function getFilteredCount($query): int
    {
        return (clone $query)->count();
    }

    private function getTotalCount(): int
    {
        return DB::table('chamados')->whereNull('deleted_at')->count();
    }

    private function applyPagination($query, int $start, int $length): void
    {
        $query->offset($start)->limit($length);
    }

}