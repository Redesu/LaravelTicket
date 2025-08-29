<?php

namespace App\Http\Controllers;

use App\Services\Analytics\EstatisticasService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        private EstatisticasService $estatisticasService
    ) {
    }
    public function showDashboard()
    {
        $stats = $this->estatisticasService->getEstatisticas();
        return view('admin.dashboard', compact('stats'));
    }

    private function getEstatisticas(): JsonResponse
    {
        $result = $this->estatisticasService->getEstatisticas();
        return $result->toJsonResponse();
    }
}