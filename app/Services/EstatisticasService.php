<?php
namespace App\Services;

use App\DTOs\GetEstatisticasResponseDTO;
use DB;

class EstatisticasService
{
    public function getEstatisticas(): GetEstatisticasResponseDTO
    {
        try {
            $qntdNovosChamados = $this->getNovosChamados();
            $porcentagemChamadosFechados = $this->getPorcentagemChamadosFechados();
            $qntdChamadosUrgentes = $this->getQntdChamadosUrgentes();
            $usuariosRegistrados = $this->getUsuariosRegistrados();

            return GetEstatisticasResponseDTO::success(
                qntdNovosChamados: $qntdNovosChamados,
                porcentagemChamadosFechados: $porcentagemChamadosFechados,
                qntdChamadosUrgentes: $qntdChamadosUrgentes,
                qntdUsuarios: $usuariosRegistrados
            );
        } catch (\Exception $e) {
            return GetEstatisticasResponseDTO::error(
                message: 'Não foi possível obter as estatísticas.',
                error: $e->getMessage()
            );
        }
    }

    private function getNovosChamados(): int
    {
        return DB::table('chamados')
            ->where('created_at', '>=', 'DATE_SUB(NOW(), INTERVAL 7 DAY)')
            ->count();
    }

    private function getPorcentagemChamadosFechados(): ?string
    {
        return DB::table('chamados')
            ->selectRaw('
        CAST(ROUND(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) AS CHAR) as PORCENTAGEM_FECHADOS
        ', ['Finalizado'])
            ->value('PORCENTAGEM_FECHADOS');
    }

    private function getQntdChamadosUrgentes(): int
    {
        return DB::table('chamados')
            ->where('prioridade', 'Urgente')
            ->count();
    }

    private function getUsuariosRegistrados(): int
    {
        return DB::table('users')->count();
    }

}