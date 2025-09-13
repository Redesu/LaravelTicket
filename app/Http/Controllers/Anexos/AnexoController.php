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

        $filePath = 'anexos/chamados/uploads/' . $anexo->caminho_arquivo;

        if (!Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'Arquivo não encontrado no servidor.');
        }

        return Storage::download('storage/public/' . $filePath, $anexo->nome_original);
    }

    public function view($id)
    {
        // TODO - transformar em service
        $anexo = Anexo::find($id);

        if (!$anexo) {
            return redirect()->back()->with('error', 'Anexo não encontrado.');
        }

        $filePath = storage_path('public/anexos/chamados/uploads/' . $anexo->caminho_arquivo);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Arquivo não encontrado no servidor.');
        }

        $mimeType = mime_content_type($filePath);
        return response()->file($filePath, ['Content-Type' => $mimeType]);
    }
}
