<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Anexo extends Model
{
    protected $table = 'anexos';

    protected $fillable = [
        'anexavel_type',
        'anexavel_id',
        'nome_original',
        'nome_arquivo',
        'caminho',
        'mime_type',
        'tamanho',
        'uploaded_by',
    ];

    public function anexavel(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
