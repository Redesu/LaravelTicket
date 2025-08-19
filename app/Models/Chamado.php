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
        'departamento_id',
        'data_abertura',
        'data_fechamento',
    ];



    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comentarios()
    {
        return $this->hasMany(ChamadoComentario::class)->orderBy('created_at');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function getCommentsOnly()
    {
        return $this->comentarios()->comments()->get();
    }

    public function getEditHistory()
    {
        return $this->comentarios()->edits()->get();
    }

    public function getSolution()
    {
        return $this->comentarios()->solutions()->first();
    }

    public function hasSolution()
    {
        return $this->comentarios()->solutions()->exists();
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

    public function buscarChamadoPorId(int $id)
    {
        return Chamado::with(['categoria', 'departamento', 'usuario'])
            ->select([
                'id',
                'titulo',
                'descricao',
                'status',
                'prioridade',
                'created_at as data_abertura'
            ])
            ->findOrFail($id);
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
                'c.user_id as usuario_id',
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


    public function deletarChamado(int $id, int $userId): int
    {
        $query = DB::table('chamados')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->delete();

        if ($query === false) {
            throw new \Exception('Erro ao deletar o chamado.');
        }

        return $query;
    }

    public static function buscarEstatisticar()
    {
        $qntdNovosChamados = DB::table('chamados')
            ->where('created_at', '>=', 'DATE_SUB(NOW(), INTERVAL 7 DAY)')
            ->count();

        $porcentagemChamadosFechados = DB::table('chamados')
            ->selectRaw('
        CAST(ROUND(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) AS CHAR) as PORCENTAGEM_FECHADOS
        ', ['Finalizado'])
            ->value('PORCENTAGEM_FECHADOS');

        $qntdChamadosUrgentes = DB::table('chamados')
            ->where('prioridade', 'Urgente')
            ->count();

        $usuariosRegistrados = DB::table('users')->count();

        return (object) [
            'qntdNovosChamados' => strval($qntdNovosChamados),
            'porcentagemChamadosFechados' => strval($porcentagemChamadosFechados),
            'qntdChamadosUrgentes' => strval($qntdChamadosUrgentes),
            'qntdUsuarios' => strval($usuariosRegistrados)
        ];
    }
}


