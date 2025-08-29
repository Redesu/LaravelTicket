<?php

namespace App\Services\Utilities;

use App\Models\Categoria;
use App\Models\Chamado;
use App\Models\Departamento;
use App\Models\User;

class NameResolutionService
{
    private const ID_TO_NAME_MAPPINGS = [
        'user_id' => ['model' => User::class, 'field' => 'name'],
        'departamento_id' => ['model' => Departamento::class, 'field' => 'nome'],
        'categoria_id' => ['model' => Categoria::class, 'field' => 'nome'],
    ];

    public function getOriginalNames(Chamado $chamado): array
    {
        return [
            'user_id' => $chamado->usuario?->name ?? 'N/A',
            'departamento_id' => $chamado->departamento?->nome ?? 'N/A',
            'categoria_id' => $chamado->categoria?->nome ?? 'N/A',
        ];
    }


    public function resolveOldValue(string $field, $oldValue, array $originalNames): string
    {
        if (isset(self::ID_TO_NAME_MAPPINGS[$field])) {
            return $originalNames[$field] ?? 'N/A';
        }
        return $oldValue;
    }


    public function resolveNewValue(string $field, $newValue): string
    {
        if (!isset(self::ID_TO_NAME_MAPPINGS[$field]) || !$newValue) {
            return $newValue ?? 'N/A';
        }

        $mapping = self::ID_TO_NAME_MAPPINGS[$field];
        $model = $mapping['model']::find($newValue);

        return $model ? $model->{$mapping['field']} : 'unknown';
    }

}