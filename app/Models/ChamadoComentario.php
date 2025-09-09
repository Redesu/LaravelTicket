<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ChamadoComentario extends Model
{
    protected $fillable = [
        'chamado_id',
        'usuario_id',
        'descricao',
        'tipo',
        'changes'
    ];

    protected $casts = [
        'changes' => 'array', // Cast changes to array for JSON storage
    ];

    public function chamado()
    {
        return $this->belongsTo(Chamado::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function anexavel(): MorphMany
    {
        return $this->morphMany(Anexo::class, 'anexavel');
    }

    public function scopeComments($query)
    {
        return $query->where('tipo', 'comment');
    }

    public function scopeEdits($query)
    {
        return $query->where('tipo', 'edit');
    }

    public function scopeSolutions($query)
    {
        return $query->where('tipo', 'solution');
    }
}
