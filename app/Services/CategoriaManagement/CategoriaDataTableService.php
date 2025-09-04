<?php
namespace App\Services\CategoriaManagement;

use App\DTOs\ChamadoManagement\Data\CategoriaDataTableRowDTO;
use App\DTOs\DataTable\DataTableRequestDTO;
use App\DTOs\DataTable\DataTableResponseDTO;
use App\Http\Requests\DataTableCategoriaRequest;
use DB;

class CategoriaDataTableService
{

    public function getCategoriasFromDataTable(DataTableRequestDTO $request): DataTableResponseDTO
    {
        $query = $this->buildBaseQuery();

        $this->applySearch($query, $request->getSearchValue());

        $recordsFiltered = $this->getFilteredCount($query);
        $recordsTotal = $this->getTotalCount();

        $this->applyPagination($query, $request->getStart(), $request->getLength());

        $categorias = $query->get();

        $categoriaDTOs = $categorias->map(function ($categoria) {
            return CategoriaDataTableRowDTO::fromDataBaseRow($categoria)->toArray();
        })->toArray();

        return DataTableResponseDTO::create(
            $request->getDraw(),
            $recordsTotal,
            $recordsFiltered,
            $categoriaDTOs
        );
    }

    private function buildBaseQuery()
    {
        return DB::table('categorias as c')
            ->whereNull('deleted_at')
            ->select([
                'c.id',
                'c.nome',
                'c.created_at as criado_em'
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
        return DB::table('categorias')->whereNull('deleted_at')->count();
    }

    private function applyPagination($query, int $start, int $length): void
    {
        $query->offset($start)->limit($length);
    }
}