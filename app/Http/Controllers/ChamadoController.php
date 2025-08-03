<?php

namespace App\Http\Controllers;

use DB;
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

        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar chamado',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
