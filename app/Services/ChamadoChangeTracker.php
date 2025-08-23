<?php

namespace App\Services;

use App\DTOs\ChamadoOriginalDataDTO;
use App\Models\Chamado;

class ChamadoChangeTracker
{
    private ChamadoOriginalDataDTO $originalData;
    private array $originalNames;
    private array $fieldNames;
    private NameResolutionService $nameResolver;

    public function __construct(Chamado $chamado, array $fieldNames, NameResolutionService $nameResolver)
    {
        $this->originalData = ChamadoOriginalDataDTO::fromChamado($chamado);
        $this->fieldNames = $fieldNames;
        $this->nameResolver = $nameResolver;
        $this->originalNames = $this->nameResolver->getOriginalNames($chamado);
    }

    public function getChanges(array $updateData): array
    {
        $changes = [];
        $originalArray = $this->originalData->toArray();

        foreach ($originalArray as $field => $oldValue) {
            if (isset($updateData[$field]) && $updateData[$field] !== $oldValue) {
                $fieldName = $this->fieldNames[$field] ?? $field;

                $changes[$fieldName] = [
                    'old' => $this->nameResolver->resolveOldValue($field, $oldValue, $this->originalNames),
                    'new' => $this->nameResolver->resolveNewValue($field, $updateData[$field])
                ];
            }
        }
        return $changes;
    }
}