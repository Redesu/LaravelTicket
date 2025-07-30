<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    //
    protected $table = 'departamentos';
    protected $fillable = ['nome', 'descricao'];

    public function chamado()
    {
        return $this->belongsTo(Chamado::class);
    }
}
