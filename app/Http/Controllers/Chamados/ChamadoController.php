<?php

namespace App\Http\Controllers\Chamados;

use App\DTOs\DataTableRequestDTO;
use App\DTOs\InsertChamadoDTO;
use App\DTOs\UpdateChamadoDTO;
use App\Http\Requests\AdicionarComentariosRequest;
use App\Http\Requests\DataTableChamadoRequest;
use App\Http\Requests\DeleteChamadoRequests;
use App\Http\Requests\StoreChamadoRequest;
use App\Http\Requests\UpdateChamadoRequest;
use App\Models\Chamado;
use App\Models\ChamadoComentario;
use App\Models\User;
use App\Services\ChamadoCreateService;
use App\Services\ChamadoDataTableService;
use App\Services\ChamadoUpdateService;
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
        private ChamadoDataTableService $dataTableService
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

    public function getChamados(Request $request): JsonResponse
    {
        try {
            $chamados = new Chamado();

            return response()->json($chamados->buscarChamados());

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

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

            $updateDto = UpdateChamadoDTO::fromRequest($request);
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
        try {
            $userId = Auth::id();
            $validatedData = $request->validated();

            $chamadoModel = new Chamado();
            $chamado = $chamadoModel->deletarChamado($validatedData['id'], $userId);

            if ($chamado > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Chamado deletado com sucesso'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Chamado não encontrado'
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar chamado',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function showChamado($id)
    {
        $chamado = Chamado::with(['categoria', 'departamento', 'usuario', 'comentarios.usuario'])->findOrFail($id);
        $users = User::all();
        return view('admin.chamado', compact('chamado', 'users'));
    }

    public function addComment(AdicionarComentariosRequest $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $chamado = Chamado::findOrFail($id);

            $comentario = ChamadoComentario::create([
                'chamado_id' => $chamado->id,
                'usuario_id' => Auth::id(),
                'descricao' => $validatedData['descricao'],
                'tipo' => $validatedData['tipo'] ?? 'comment',
                'changes' => $validatedData['changes'] ?? null,
            ]);

            if ($validatedData['tipo'] === 'solution') {
                $chamado->update(['status' => 'Finalizado']);
            }

            $comentario->load('usuario');

            return response()->json([
                'success' => true,
                'comentario' => $comentario,
                'usuario' => [
                    'name' => $comentario->usuario->name,
                    'avatar' => $comentario->usuario->avatar,
                ],
                'created_at' => $comentario->created_at->format('d/m/Y H:i'),
                'descricao' => $comentario->descricao,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar comentário',
                'error' => $e->getMessage()
            ], 500);
        }


    }

    public function addSolution(AdicionarComentariosRequest $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $chamado = Chamado::findOrFail($id);

            if ($chamado->hasSolution()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chamado já está resolvido.'
                ], 400);
            }

            $solucao = ChamadoComentario::create([
                'chamado_id' => $chamado->id,
                'usuario_id' => Auth::id(),
                'descricao' => $validatedData['descricao'],
                'tipo' => 'solution',
            ]);

            $chamado->update(['status' => 'Finalizado']);

            $solucao->load('usuario');

            return response()->json([
                'success' => true,
                'comentario' => $solucao,
                'message' => 'Solução adicionada e chamado marcado como resolvido.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar solução',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getEstatisticas(): JsonResponse
    {
        try {
            $stats = Chamado::buscarEstatisticar();
            return response()->json($stats);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function searchChamados(Request $request): JsonResponse
    {
        try {
            $query = DB::table('chamados');

            if ($request->has('titulo')) {
                $query->where('titulo', 'LIKE', '%' . $request->titulo . '%');
            }

            if ($request->has('descricao')) {
                $query->where('descricao', 'LIKE', '%' . $request->descricao . '%');
            }

            if ($request->has('comentarios')) {
                $query->where('comentarios', 'LIKE', '%' . $request->comentarios . '%');
            }

            if ($request->has('departamento')) {
                $query->where('departamento', $request->departamento);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }


            if ($request->has('prioridade')) {
                $query->where('prioridade', $request->titulo);
            }

            if ($request->has('from_date')) {
                $query->where('created_at', '>=', $request->from_date);
            }

            if ($request->has('to_date')) {
                $query->where('created_at', '<=', $request->to_date);
            }

            $chamados = $query->orderBy('titulo')->get();

            return response()->json($chamados);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
