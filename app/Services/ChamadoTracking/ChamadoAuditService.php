<?php

namespace App\Services\ChamadoTracking;

use App\Models\ChamadoComentario;
use Illuminate\Support\Facades\Auth;

class ChamadoAuditService
{
    public function logChanges(int $chamadoId, array $changes): void
    {
        ChamadoComentario::create([
            'chamado_id' => $chamadoId,
            'usuario_id' => Auth::id(),
            'descricao' => 'Chamado editado por: ' . Auth::user()->name,
            'tipo' => 'edit',
            'changes' => json_encode($changes),
        ]);
    }
}