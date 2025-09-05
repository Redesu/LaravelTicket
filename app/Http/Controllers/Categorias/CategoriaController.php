<?php

namespace App\Http\Controllers\Categorias;

use App\DTOs\CategoriaManagement\Requests\CreateCategoriaRequestDTO;
use App\DTOs\CategoriaManagement\Requests\DeleteCategoriaRequestDTO;
use App\DTOs\CategoriaManagement\Requests\UpdateCategoriaRequestDTO;
use App\DTOs\DataTable\DataTableRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataTableCategoriaRequest;
use App\Http\Requests\DeleteCategoriaRequest;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Services\CategoriaManagement\CategoriaCreateService;
use App\Services\CategoriaManagement\CategoriaDataTableService;
use App\Services\CategoriaManagement\CategoriaDeleteService;
use App\Services\CategoriaManagement\CategoriaUpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{

    public function __construct(
        private CategoriaDataTableService $dataTableService,
        private CategoriaCreateService $categoriaCreateService,
        private CategoriaDeleteService $categoriaDeleteService,
        private CategoriaUpdateService $categoriaUpdateService
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

    public function insertCategoria(StoreCategoriaRequest $request): JsonResponse
    {
        $categoriaDTO = CreateCategoriaRequestDTO::fromRequest($request);
        $result = $this->categoriaCreateService->criarCategoria($categoriaDTO);

        return $result->toJsonResponse();
    }

    public function updateCategoria(UpdateCategoriaRequest $request, $id): JsonResponse
    {
        $updateDTO = UpdateCategoriaRequestDTO::fromRequest($request);
        $result = $this->categoriaUpdateService->updateCategoria($updateDTO, $id);

        return $result->toJsonResponse();
    }

    public function deleteCategoria(DeleteCategoriaRequest $request): JsonResponse
    {
        $deleteDTO = DeleteCategoriaRequestDTO::fromRequest($request);
        $result = $this->categoriaDeleteService->deleteCategoria($deleteDTO);

        return $result->toJsonResponse();
    }
}
