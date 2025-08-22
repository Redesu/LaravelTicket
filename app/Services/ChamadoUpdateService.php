<?php
namespace App\Services;

use App\DTOs\ChamadoOriginalDataDTO;
use App\DTOs\ChamadoUpdateResultDTO;
use App\DTOs\UpdateChamadoDTO;
use App\Models\Chamado;
use App\Models\ChamadoComentario;
use App\Models\User;
use Auth;

class ChamadoUpdateService
{
    private const FIELD_NAMES = [
        'titulo' => 'Título',
        'descricao' => 'Descrição',
        'prioridade' => 'Prioridade',
        'departamento' => 'Departamento',
        'categoria' => 'Categoria',
        'status' => 'Status',
        'user_id' => 'Usuário Responsável'
    ];


    public function updateChamado(UpdateChamadoDTO $updateDTO, int $id): ChamadoUpdateResultDTO
    {
        $chamado = Chamado::with('categoria', 'departamento', 'usuario')->findOrFail($id);

        if ($chamado->status === 'Finalizado') {
            return new ChamadoUpdateResultDTO(
                success: false,
                message: 'Chamado já está finalizado e não pode ser editado.'
            );
        }

        $originalData = ChamadoOriginalDataDTO::fromChamado($chamado);

        $updateData = $updateDTO->toArray();
        $newUserName = $this->getNewUserName($updateDTO, $chamado);


        $chamado->update($updateData);

        $changes = $this->trackChanges($originalData, $updateData, $newUserName);

        if (!empty($changes)) {
            $this->createUpdateComment($chamado->id, $changes);
        }

        return new ChamadoUpdateResultDTO(
            success: true,
            message: 'Chamado atualizado com sucesso',
            newData: $updateData,
            changes: $changes,
            chamado: $chamado
        );
    }


    private function getNewUserName(UpdateChamadoDTO $updateDTO, Chamado $chamado): ?string
    {
        if ($updateDTO->getUserId() && $updateDTO->getUserId() !== $chamado->user_id) {
            $newUser = User::find($updateDTO->getUserId());
            return $newUser ? $newUser->name : 'Unknown';
        }
        return null;
    }

    private function trackChanges(
        ChamadoOriginalDataDTO $originalData,
        array $updateData,
        ?string $newUserName
    ): array {
        $changes = [];
        $originalArray = $originalData->toArray();

        foreach ($originalArray as $field => $oldValue) {
            if (isset($updateData[$field]) && $updateData[$field] !== $oldValue) {
                $fieldName = self::FIELD_NAMES[$field] ?? $field;

                if ($field === 'user_id') {
                    $changes[$fieldName] = [
                        'old' => $oldValue,
                        'new' => $newUserName
                    ];
                } else {
                    $changes[$fieldName] = [
                        'old' => $oldValue,
                        'new' => $updateData[$field]
                    ];
                }
            }
        }

        return $changes;
    }

    private function createUpdateComment(int $chamadoId, array $changes): void
    {
        ChamadoComentario::create([
            'chamado_id' => $chamadoId,
            'usuario_id' => Auth::id(),
            'descricao' => 'Chamado editado por: ' . Auth::user()->name,
            'tipo' => 'edit',
            'changes' => json_encode($changes),
        ]);
    }
}