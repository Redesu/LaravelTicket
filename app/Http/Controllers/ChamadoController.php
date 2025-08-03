<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChamadoController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.chamados');
    }

    public function getChamados(Request $request): JsonResponse
    {
        try {
            $query = DB::table('chamados');

            if ($request->has('titulo') && !empty($request->Titulo)) {
                $query->where('titulo', 'like', '%' . $request->Titulo . '%');
            }

            if ($request->has('departamento') && !empty($request->departamento)) {
                $query->where('departamento', 'like', '%' . $request->departamento . '%');
            }

            if ($request->has('categoria') && !empty($request->categoria)) {
                $query->where('categoria', 'like', '%' . $request->categoria . '%');
            }

            if ($request->has('prioridade') && !empty($request->prioridade)) {
                $query->where('prioridade', 'like', '%' . $request->prioridade . '%');
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', '=', $request->status);
            }

            $sortBy = $request->get('SortBy', 'id');
            $sortOrder = $request->get('SortOrder', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            $chamados = $query->get();

            return response()->json($chamados);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function insertChamado(Request $request): JsonResponse
    {
        try {
            $request->validade([
                'Titulo' => 'required|string|max:255',
                'Descricao' => 'required|string|max:100',
                'Prioridade' => 'required|string|max:100',
                'categoria_id' => 'required|numeric|min:1',
                'departamento_id' => 'required|numeric|min:1'
            ]);

            $id = DB::table('chamados')->insertGetId([
                'titulo' => $request->Titulo,
                'descricao' => $request->Descricao,
                'prioridade' => $request->Prioridade,
                'categoria_id' => $request->categoria_id,
                'departamento_id' => $request->departamento_id
            ]);

            $chamado = DB::table('chamados')->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Chamado criado com sucesso',
                'data' => $chamado
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar chamado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateChamado(Request $request): JsonResponse
    {
        try {
            $request->validade([
                'Titulo' => 'required|string|max:255',
                'Descricao' => 'required|string|max:100',
                'Prioridade' => 'required|string|max:100',
                'categoria_id' => 'required|numeric|min:1',
                'departamento_id' => 'required|numeric|min:1'
            ]);

            $affected = DB::table('chamados')
                ->where('id', $request->ID)
                ->update([
                    'titulo' => $request->Titulo,
                    'descricao' => $request->Descricao,
                    'prioridade' => $request->Prioridade,
                    'categoria_id' => $request->categoria_id,
                    'departamento_id' => $request->departamento_id

                ]);

            if ($affected > 0) {
                $chamado = DB::table('chamados')->where('id', $request->ID)->first();
                return response()->json([
                    'success' => true,
                    'message' => 'Chamado atualizado com sucesso',
                    'data' => $chamado
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Chamado não encontrado'
                ], 404);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
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
            $request->validate([
                'ID' => 'required|numeric|exists:chamados,id'
            ]);

            $affected = DB::table('chamados')
                ->where('id', $request->ID)
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
                'message' => 'Erro ao deletar chamado',
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

