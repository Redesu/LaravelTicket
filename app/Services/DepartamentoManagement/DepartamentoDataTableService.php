<?php
namespace App\Services\DepartamentoManagement;

use App\DTOs\DataTable\DataTableRequestDTO;
use App\DTOs\DataTable\DataTableResponseDTO;
use App\DTOs\DepartamentoManagement\Data\DepartamentoDataRableRowDTO;
use DB;

class DepartamentoDataTableService
{

    public function getDepartamentosFromDataTable(DataTableRequestDTO $request): DataTableResponseDTO
    {
        $query = $this->buildBaseQuery();

        $this->applySearch($query, $request->getSearchValue());

        $recordsFiltered = $this->getFilteredCount($query);
        $recordsTotal = $this->getTotalCount();

        $this->applyPagination($query, $request->getStart(), $request->getLength());

        $departamentos = $query->get();

        $departamentoDTOs = $departamentos->map(function ($departamento) {
            return DepartamentoDataRableRowDTO::fromDataBaseRow($departamento)->toArray();
        })->toArray();

        return DataTableResponseDTO::create(
            $request->getDraw(),
            $recordsTotal,
            $recordsFiltered,
            $departamentoDTOs
        );
    }

    private function buildBaseQuery()
    {
        return DB::table('departamentos as d')
            ->whereNull('deleted_at')
            ->select([
                'd.id',
                'd.nome',
                'd.descricao',
                'd.created_at as criado_em'
            ]);
    }

    private function applySearch($query, string $searchValue): void
    {
        if (empty($searchValue)) {
            return;
        }

        $query->where(function ($q) use ($searchValue) {
            $q->where('nome', 'like', "%{$searchValue}%");
        });
    }

    private function getFilteredCount($query): int
    {
        return (clone $query)->count();
    }

    private function getTotalCount(): int
    {
        return DB::table('departamentos')->whereNull('deleted_at')->count();
    }

    private function applyPagination($query, int $start, int $length): void
    {
        $query->offset($start)->limit($length);
    }
}