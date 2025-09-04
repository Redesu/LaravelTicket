<?php

namespace App\Http\Controllers\Categorias;

use App\DTOs\DataTable\DataTableRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataTableCategoriaRequest;
use App\Services\CategoriaManagement\CategoriaDataTableService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{

    public function __construct(
        private CategoriaDataTableService $dataTableService,
    ) {
    }

    public function showCategorias()
    {
        return view('admin.categorias');
    }

    public function getCategoriasDataTablesData(DataTableCategoriaRequest $request): JsonResponse
    {
        $dataTableRequest = DataTableRequestDTO::fromRequest($request->all());
        $response = $this->dataTableService->getCategoriasFromDataTable($dataTableRequest);

        return $response->toJsonResponse();

    }

    public function insertCategoria()
    {

    }

    public function updateCategoria()
    {

    }

    public function deleteCategoria()
    {

    }
}
