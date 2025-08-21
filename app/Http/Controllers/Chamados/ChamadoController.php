<?php

namespace App\Http\Controllers\Chamados;

use App\DTOs\InsertChamadoDTO;
use App\Http\Requests\AdicionarComentariosRequest;
use App\Http\Requests\DataTableChamadoRequest;
use App\Http\Requests\DeleteChamadoRequests;
use App\Http\Requests\StoreChamadoRequest;
use App\Http\Requests\UpdateChamadoRequest;
use App\Models\Chamado;
use App\Models\ChamadoComentario;
use App\Models\User;
use Auth;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class ChamadoController extends Controller
{

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
        try {
            $draw = $request->get('draw');
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $searchValue = $request->get('search')['value'] ?? '';

            $validateFilters = $request->validated();

            $chamadoModel = new Chamado();
            $dataTables = $chamadoModel->buscarChamadoDataTables($draw, $start, $length, $searchValue, $validateFilters);
            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $dataTables['recordsTotal'],
                'recordsFiltered' => $dataTables['recordsFiltered'],
                'data' => $dataTables['data']->map(function ($chamado) {
                    return [
                        'id' => $chamado->id,
                        'titulo' => $chamado->titulo,
                        'descricao' => $chamado->descricao,
                        'status' => $chamado->status,
                        'prioridade' => $chamado->prioridade,
                        'categoria' => $chamado->categoria,
                        'departamento' => $chamado->departamento,
                        'usuario_id' => $chamado->usuario_id,
                        'data_abertura' => $chamado->data_abertura ?
                            date('Y-m-d H:i', strtotime($chamado->data_abertura)) : ''
                    ];
                })
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function insertChamado(StoreChamadoRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $chamadoDTO = InsertChamadoDTO::fromValidatedInsertRequest($validatedData);

            $chamadoModel = new Chamado();
            $chamado = $chamadoModel->criarChamado($chamadoDTO);

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
            $chamado = Chamado::with(['categoria', 'departamento', 'usuario'])->findOrFail($id);
            if ($chamado->status === 'Finalizado') {
                return response()->json([
                    'success' => false,
                    'message' => 'Chamado já está finalizado e não pode ser editado.'
                ], 400);
            }

            $originalData = [
                'titulo' => $chamado->titulo,
                'descricao' => $chamado->descricao,
                'prioridade' => $chamado->prioridade,
                'status' => $chamado->status,
                'categoria' => $chamado->categoria->nome,
                'departamento' => $chamado->departamento->nome,
                'user_id' => $chamado->usuario->name
            ];

            $validatedData = $request->validated();

            if (isset($validatedData['user_id']) && $validatedData['user_id'] != $chamado->user_id) {
                $newUser = User::find($validatedData['user_id']);
                $validatedData['user_name'] = $newUser ? $newUser->name : 'Unknown';
            }

            $chamado->update($validatedData);

            $changes = [];
            $fieldNames = [
                'titulo' => 'Título',
                'descricao' => 'Descrição',
                'prioridade' => 'Prioridade',
                'departamento_id' => 'Departamento',
                'categoria_id' => 'Categoria',
                'status' => 'Status',
                'user_id' => 'Usuário Responsável'
            ];

            foreach ($originalData as $field => $oldValue) {
                if (isset($validatedData[$field]) && $validatedData[$field] != $oldValue) {

                    if ($field === 'user_id') {
                        $changes[$fieldNames[$field]] = [
                            'old' => $oldValue,
                            'new' => $validatedData['user_name']
                        ];
                    } else {
                        $changes[$fieldNames[$field]] = [
                            'old' => $oldValue,
                            'new' => $validatedData[$field]
                        ];
                    }
                }
            }

            if (!empty($changes)) {
                ChamadoComentario::create([
                    'chamado_id' => $chamado->id,
                    'usuario_id' => Auth::id(),
                    'descricao' => 'Chamado editado por: ' . Auth::user()->name,
                    'tipo' => 'edit',
                    'changes' => json_encode($changes),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Chamado atualizado com sucesso',
                'newData' => $validatedData,
                'changes' => $changes,
                'data' => $chamado
            ], 200);
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
