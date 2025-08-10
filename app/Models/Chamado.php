<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;


class Chamado extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'chamados';

    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'prioridade',
        'user_id',
        'categoria_id',
        'solucao',
        'comentarios',
        'departamento_id',
        'data_abertura',
        'data_fechamento',
    ];



    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function buscarChamados()
    {

        $query = DB::table('chamados as c')
            ->leftJoin('categorias as cat', 'c.categoria_id', '=', 'cat.id')
            ->leftJoin('departamentos as dep', 'c.departamento_id', '=', 'dep.id')
            ->select([
                'c.id',
                'c.titulo',
                'c.descricao',
                'c.status',
                'c.prioridade',
                'cat.nome as categoria',
                'dep.nome as departamento',
                'c.created_at as data_abertura'
            ]);

        return $query->get();
    }

    public function buscarChamadoDataTables(string $draw, int $start = 0, int $length = 10, string $searchValue = '')
    {
        $query = DB::table('chamados as c')
            ->leftJoin('categorias as cat', 'c.categoria_id', '=', 'cat.id')
            ->leftJoin('departamentos as dep', 'c.departamento_id', '=', 'dep.id')
            ->select([
                'c.id',
                'c.titulo',
                'c.descricao',
                'c.status',
                'c.prioridade',
                'cat.nome as categoria',
                'dep.nome as departamento',
                'c.created_at as data_abertura'
            ]);

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('titulo', 'like', "%{$searchValue}%")
                    ->orWhere('descricao', 'like', "%{$searchValue}%")
                    ->orWhere('status', 'like', "%{$searchValue}%")
                    ->orWhere('prioridade', 'like', "%{$searchValue}%");
            });
        }

        $totalRecords = DB::table('chamados')->count();

        $filteredRecords = $query->count();

        $records = $query
            ->skip($start)
            ->take($length)
            ->get();

        return [
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $records
        ];
    }

    /*$id = DB::table('chamados')->insertGetId([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'user_id' => $userId,
                'prioridade' => $request->prioridade,
                'categoria_id' => $request->categoria_id,
                'departamento_id' => $request->departamento_id
            ]);

            $chamado = DB::table('chamados')->where('id', $id)->first();*/
    public function criarChamado(string $titulo, string $descricao, int $userId, string $prioridade, int $categoriaId, int $departamentoId)
    {

        $query = DB::table('chamados')->insertGetId([
            'titulo' => $titulo,
            'descricao' => $descricao,
            'user_id' => $userId,
            'prioridade' => $prioridade,
            'categoria_id' => $categoriaId,
            'departamento_id' => $departamentoId,
            'created_at' => now(),
        ]);

        $chamado = DB::table('chamados')->where('id', $query)->first();

        if ($chamado === null) {
            throw new \Exception('Chamado não encontrado após inserção.');
        }
        return $chamado;
    }


    public function editarChamado(int $id, string $titulo, string $descricao, string $prioridade, string $status, string $categoria, string $departamento)
    {
        $departamentoId = DB::table('departamentos')->where('nome', $departamento)->value('id');

        $categoriaId = DB::table('categorias')->where('nome', $categoria)->value('id');

        $query = DB::table('chamados')
            ->where('id', $id)
            ->update([
                'titulo' => $titulo,
                'descricao' => $descricao,
                'prioridade' => $prioridade,
                'status' => $status,
                'categoria_id' => $categoriaId,
                'departamento_id' => $departamentoId,
                'updated_at' => now(),
            ]);

        if ($query === false) {
            throw new \Exception('Erro ao atualizar o chamado.');
        }

        return $query > 0;
    }
}


