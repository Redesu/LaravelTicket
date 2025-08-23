<?php

namespace App\Services;
use App\Models\Chamado;
use App\Services\ChamadoChangeTracker;

class ChamadoChangeTrackingService
{
    private const FIELD_NAMES = [
        'titulo' => 'Título',
        'descricao' => 'Descrição',
        'prioridade' => 'Prioridade',
        'departamento_id' => 'Departamento',
        'categoria_id' => 'Categoria',
        'status' => 'Status',
        'user_id' => 'Usuário Responsável'
    ];

    public function createTracker(Chamado $chamado): ChamadoChangeTracker
    {
        $nameResolver = new NameResolutionService();
        return new ChamadoChangeTracker($chamado, self::FIELD_NAMES, $nameResolver);
    }
}