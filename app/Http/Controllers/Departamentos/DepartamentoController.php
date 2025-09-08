<?php

namespace App\Http\Controllers\Departamentos;

use App\DTOs\DataTable\DataTableRequestDTO;
use App\DTOs\DepartamentoManagement\Requests\CreateDepartamentoRequestDTO;
use App\DTOs\DepartamentoManagement\Requests\DeleteDepartamentoRequestDTO;
use App\DTOs\DepartamentoManagement\Requests\UpdateDepartamentoRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataTableDepartamentoRequest;
use App\Http\Requests\DeleteDepartamentoRequest;
use App\Http\Requests\StoreDepartamentoRequest;
use App\Http\Requests\UpdateDepartamentoRequest;
use App\Services\DepartamentoManagement\DepartamentoCreateService;
use App\Services\DepartamentoManagement\DepartamentoDataTableService;
use App\Services\DepartamentoManagement\DepartamentoDeleteService;
use App\Services\DepartamentoManagement\DepartamentoUpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function __construct(
        private DepartamentoDataTableService $dataTableService,
        private DepartamentoCreateService $departamentoCreateService,
        private DepartamentoDeleteService $departamentoDeleteService,
        private DepartamentoUpdateService $departamentoUpdateService
    ) {
    }

    public function showDepartamentos()
    {
        return view('admin.departamentos');
    }

    public function getDepartamentosDataTablesData(DataTableDepartamentoRequest $request): JsonResponse
    {
        $dataTableRequest = DataTableRequestDTO::fromRequest($request->all());
        $response = $this->dataTableService->getDepartamentosFromDataTable($dataTableRequest);

        return $response->toJsonResponse();
    }

    public function insertDepartamento(StoreDepartamentoRequest $request): JsonResponse
    {
        $departamentoDTO = CreateDepartamentoRequestDTO::fromRequest($request);
        $result = $this->departamentoCreateService->criarDepartamento($departamentoDTO);

        return $result->toJsonResponse();
    }

    public function updateDepartamento(UpdateDepartamentoRequest $request, $id): JsonResponse
    {
        $updateDTO = UpdateDepartamentoRequestDTO::fromRequest($request);
        $result = $this->departamentoUpdateService->updateDepartamento($updateDTO, $id);

        return $result->toJsonResponse();
    }

    public function deleteDepartamento(DeleteDepartamentoRequest $request): JsonResponse
    {
        $deleteDTO = DeleteDepartamentoRequestDTO::fromRequest($request);
        $result = $this->departamentoDeleteService->deleteDepartamento($deleteDTO);

        return $result->toJsonResponse();
    }
}
