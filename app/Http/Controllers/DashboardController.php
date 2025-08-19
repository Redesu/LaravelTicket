<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        $stats = Chamado::buscarEstatisticar();
        return view('admin.dashboard', compact('stats'));
    }
}