<?php

namespace App\Http\Controllers\Chamados;

use App\DTOs\AddComentarioRequestDTO;
use App\DTOs\AddSolutionRequestDTO;
use App\DTOs\DataTableRequestDTO;
use App\DTOs\DeleteChamadoRequestDTO;
use App\DTOs\InsertChamadoDTO;
use App\DTOs\ChamadoUpdateRequestDTO;
use App\Http\Requests\AdicionarComentariosRequest;
use App\Http\Requests\AdicionarSolucaoRequest;
use App\Http\Requests\DataTableChamadoRequest;
use App\Http\Requests\DeleteChamadoRequests;
use App\Http\Requests\StoreChamadoRequest;
use App\Http\Requests\UpdateChamadoRequest;
use App\Models\Chamado;
use App\Models\ChamadoComentario;
use App\Models\User;
use App\Services\AddComentarioService;
use App\Services\AddSolucaoService;
use App\Services\ChamadoCreateService;
use App\Services\ChamadoDataTableService;
use App\Services\ChamadoDeleteService;
use App\Services\ChamadoUpdateService;
use App\Services\EstatisticasService;
use Auth;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class ChamadoController extends Controller
{

    public function __construct(
        private ChamadoUpdateService $updateChamadoService,
        private ChamadoCreateService $createChamadoService,
        private ChamadoDataTableService $dataTableService,
        private ChamadoDeleteService $deleteChamadoService,
        private AddComentarioService $addComentarioService,
        private AddSolucaoService $addSolucaoService,

    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.chamados', compact('users'));
    }

    public function getDataTablesData(DataTableChamadoRequest $request): JsonResponse
    {
        $dataTableRequest = DataTableRequestDTO::fromRequest($request->all());

        $response = $this->dataTableService->getChamadosFromDataTable($dataTableRequest);

        return $response->toJsonResponse();
    }

    public function insertChamado(StoreChamadoRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $chamadoDTO = InsertChamadoDTO::fromValidatedInsertRequest($validatedData);

            $chamado = $this->createChamadoService->criarChamado($chamadoDTO);

            return response()->json([
                'success' => true,
                'message' => 'Chamado criado com sucesso',
                'data' => $chamado
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar chamado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateChamado(UpdateChamadoRequest $request, $id): JsonResponse
    {
        try {

            $updateDto = ChamadoUpdateRequestDTO::fromRequest($request);
            $result = $this->updateChamadoService->updateChamado($updateDto, $id);


            return $result->toJsonResponse();

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar chamado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteChamado(DeleteChamadoRequests $request): JsonResponse
    {
        $deleteDto = DeleteChamadoRequestDTO::fromRequest($request);

        $result = $this->deleteChamadoService->deleteChamado($deleteDto);

        return $result->toJsonResponse();
    }

    public function showChamado($id)
    {
        $chamado = Chamado::with(['categoria', 'departamento', 'usuario', 'comentarios.usuario'])->findOrFail($id);
        $users = User::all();
        return view('admin.chamado', compact('chamado', 'users'));
    }

    public function addComment(AdicionarComentariosRequest $request, $id): JsonResponse
    {
        $addCommentDTO = AddComentarioRequestDTO::fromRequest($request);
        $result = $this->addComentarioService->addComentario($addCommentDTO, $id);

        return $result->toJsonResponse();

    }

    public function addSolution(AdicionarSolucaoRequest $request, $id): JsonResponse
    {
        $addSolucaoDTO = AddSolutionRequestDTO::fromRequest($request);
        $result = $this->addSolucaoService->addSolucao($addSolucaoDTO, $id);

        return $result->toJsonResponse();
    }
}
