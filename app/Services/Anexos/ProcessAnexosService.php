<?php
namespace App\Services\Anexos;

use Illuminate\Http\UploadedFile;

class ProcessAnexosService
{

    public function processAnexos(array $uploadedFiles, $anexavel): void
    {
        foreach ($uploadedFiles as $file) {
            $this->validateFile($file);
            $fileData = $this->storeFile($file);
            $anexavel->anexos()->create($fileData);
        }
    }
    private function validateFile(UploadedFile $file): void
    {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/zip', 'aplicattion/rar', 'video/mp4'];
        $maxFileSize = 150 * 1024 * 1024; // 150MB

        if (!$file->isValid()) {
            throw new \Exception('Tipo de arquivo inválido.');
        }

        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new \Exception('Tipo de arquivo não permitido.');
        }

        if ($file->getSize() > $maxFileSize) {
            throw new \Exception('O arquivo excede o tamanho máximo permitido de 150MB.');
        }
    }

    private function storeFile(UploadedFile $file): array
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('anexos/chamados/uploads', $filename, 'public');

        return [
            'nome_original' => $file->getClientOriginalName(),
            'nome_arquivo' => $filename,
            'caminho' => $path,
            'mime_type' => $file->getMimeType(),
            'tamanho' => $file->getSize(),
            'uploaded_by' => auth()->id()
        ];
    }
}