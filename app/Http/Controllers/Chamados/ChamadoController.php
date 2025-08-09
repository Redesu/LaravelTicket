<?php

namespace App\Http\Controllers\Chamados;

use App\Http\Requests\StoreChamadoRequest;
use App\Http\Requests\UpdateChamadoRequest;
use App\Models\Chamado;
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
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar esta página.');
        }
        return view('admin.chamados');
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

    public function getDataTablesData(Request $request): JsonResponse
    {
        try {
            $draw = $request->get('draw');
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $searchValue = $request->get('search')['value'] ?? '';

            $chamadoModel = new Chamado();
            $dataTables = $chamadoModel->buscarChamadoDataTables($draw, $start, $length, $searchValue);
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
                        'data_abertura' => $chamado->data_abertura ?
                            date('d/m/Y H:i', strtotime($chamado->data_abertura)) : ''
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

            $chamadoModel = new Chamado();
            $chamado = $chamadoModel->criarChamado(
                $validatedData['titulo'],
                $validatedData['descricao'],
                Auth::id(),
                $validatedData['prioridade'],
                $validatedData['categoria_id'],
                $validatedData['departamento_id']
            );
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

    public function updateChamado(UpdateChamadoRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $chamadoModel = new Chamado();
            $chamado = $chamadoModel->editarChamado(
                $validatedData['id'],
                $validatedData['titulo'],
                $validatedData['descricao'],
                $validatedData['prioridade'],
                $validatedData['status'],
                $validatedData['categoria_id'],
                $validatedData['departamento_id']
            );

            return response()->json([
                'success' => true,
                'message' => 'Chamado atualizado com sucesso',
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

    public function deleteChamado(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $request->validate([
                'id' => 'required|numeric|exists:chamados,id'
            ]);

            $affected = DB::table('chamados')
                ->where('id', $request->id)
                ->where('user_id', $userId)
                ->delete();

            if ($affected > 0) {
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

    public function getChamado($id): JsonResponse
    {
        try {
            $chamado = DB::table('chamados')->where('id', $id)->first();

            if ($chamado) {
                return response()->json($chamado);
            } else {
                return response()->json(['error' => 'Chamado não encontrado'], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar chamado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getChamadoByDepartamento($departamento): JsonResponse
    {
        try {
            $chamados = DB::table('chamados')
                ->where('departamento', $departamento)
                ->get();

            return response()->json($chamados);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar chamado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getEstatisticas(): JsonResponse
    {
        try {
            $stats = [
                'total_chamados' => DB::table('chamados')->count(),
                'departamentos' => DB::table('departamentos')->count(),
                'mediaChamados' => DB::table('chamados')->avg('id'),
            ];
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

