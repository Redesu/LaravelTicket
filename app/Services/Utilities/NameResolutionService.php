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

    private const NAME_TO_ID_MAPPINGS = [
        'departamento' => ['model' => Departamento::class, 'field' => 'nome'],
        'categoria' => ['model' => Categoria::class, 'field' => 'nome'],
        'user' => ['model' => User::class, 'field' => 'name'],
    ];

    private array $nameToIdCache = [];
    private array $idToNameCache = [];

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

    /**
     * Resolve department name to ID
     */
    public function getDepartamentoIdByName(string $nome): ?int
    {
        return $this->getIdByName('departamento', $nome);
    }

    /**
     * Resolve category name to ID
     */
    public function getCategoriaIdByName(string $nome): ?int
    {
        return $this->getIdByName('categoria', $nome);
    }

    /**
     * Resolve user name to ID
     */
    public function getUserIdByName(string $name): ?int
    {
        return $this->getIdByName('user', $name);
    }

    /**
     * Generic method to resolve name to ID with caching
     */
    private function getIdByName(string $type, string $name): ?int
    {
        $cacheKey = "{$type}:{$name}";

        if (isset($this->nameToIdCache[$cacheKey])) {
            return $this->nameToIdCache[$cacheKey];
        }

        if (!isset(self::NAME_TO_ID_MAPPINGS[$type])) {
            return null;
        }

        $mapping = self::NAME_TO_ID_MAPPINGS[$type];
        $model = $mapping['model']::where($mapping['field'], $name)->first();

        $id = $model?->id;
        $this->nameToIdCache[$cacheKey] = $id;

        return $id;
    }

    /**
     * Get department name by ID (with caching)
     */
    public function getDepartamentoNameById(int $id): ?string
    {
        return $this->getNameById('departamento_id', $id);
    }

    /**
     * Get category name by ID (with caching)
     */
    public function getCategoriaNameById(int $id): ?string
    {
        return $this->getNameById('categoria_id', $id);
    }

    /**
     * Get user name by ID (with caching)
     */
    public function getUserNameById(int $id): ?string
    {
        return $this->getNameById('user_id', $id);
    }

    /**
     * Generic method to resolve ID to name with caching
     */
    private function getNameById(string $field, int $id): ?string
    {
        $cacheKey = "{$field}:{$id}";

        if (isset($this->idToNameCache[$cacheKey])) {
            return $this->idToNameCache[$cacheKey];
        }

        if (!isset(self::ID_TO_NAME_MAPPINGS[$field])) {
            return null;
        }

        $mapping = self::ID_TO_NAME_MAPPINGS[$field];
        $model = $mapping['model']::find($id);

        $name = $model?->{$mapping['field']};
        $this->idToNameCache[$cacheKey] = $name;

        return $name;
    }

    /**
     * Bulk resolve multiple names to IDs
     */
    public function bulkResolveNamesToIds(string $type, array $names): array
    {
        $resolved = [];

        foreach ($names as $name) {
            $id = $this->getIdByName($type, $name);
            if ($id !== null) {
                $resolved[$name] = $id;
            }
        }

        return $resolved;
    }

    /**
     * Bulk resolve multiple IDs to names
     */
    public function bulkResolveIdsToNames(string $field, array $ids): array
    {
        $resolved = [];

        foreach ($ids as $id) {
            $name = $this->getNameById($field, $id);
            if ($name !== null) {
                $resolved[$id] = $name;
            }
        }

        return $resolved;
    }

    /**
     * Clear all caches
     */
    public function clearCache(): void
    {
        $this->nameToIdCache = [];
        $this->idToNameCache = [];
    }

    public function transformUpdateData(array $data): array
    {
        $transformed = $data;

        if (isset($data['categoria_nome']) && $data['categoria_nome'] !== null) {
            $categoriaId = $this->getCategoriaIdByName($data['categoria_nome']);
            if ($categoriaId === null) {
                throw new \InvalidArgumentException('Categoria não encontrada: ' . $data['categoria_nome']);
            }
            $transformed['categoria_id'] = $categoriaId;
            unset($transformed['categoria_nome']);
        }

        if (isset($data['departamento_nome']) && $data['departamento_nome'] !== null) {
            $departamentoId = $this->getDepartamentoIdByName($data['departamento_nome']);
            if ($departamentoId === null) {
                throw new \InvalidArgumentException('Departamento não encontrado: ' . $data['departamento_nome']);
            }
            $transformed['departamento_id'] = $departamentoId;
            unset($transformed['departamento_nome']);
        }

        return array_filter($transformed, fn($value) => $value !== null);
    }
}