<?php

namespace App\Http\Controllers\Anexos;

use App\Http\Controllers\Controller;
use App\Models\Anexo;
use Illuminate\Http\Request;
use Storage;

class AnexoController extends Controller
{
    public function download($id)
    {
        $anexo = Anexo::find($id);

        if (!$anexo) {
            return redirect()->back()->with('error', 'Anexo não encontrado.');
        }

        $fullFilePath = Storage::disk('public')->path($anexo->caminho);

        if (!Storage::disk('public')->exists($anexo->caminho)) {
            return redirect()->back()->with('error', 'Arquivo não encontrado no servidor.');
        }

        return response()->download($fullFilePath, $anexo->nome_original);
    }
}
