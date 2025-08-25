<?php

namespace App\Models;
use App\DTOs\InsertChamadoDTO;
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


