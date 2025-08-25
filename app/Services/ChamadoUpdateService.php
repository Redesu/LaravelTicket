<?php
namespace App\Services;

use App\DTOs\ChamadoUpdateResponseDTO;
use App\DTOs\ChamadoUpdateRequestDTO;
use App\Models\Chamado;
use Auth;

class ChamadoUpdateService
{
    public function __construct(
        private ChamadoValidationService $validationService,
        private ChamadoChangeTrackingService $changeTrackingService,
        private ChamadoAuditService $auditService
    ) {
    }

    public function updateChamado(ChamadoUpdateRequestDTO $updateDTO, int $id): ChamadoUpdateResponseDTO
    {
        $chamado = Chamado::with('categoria', 'departamento', 'usuario')->findOrFail($id);

        $validationResult = $this->validationService->canChamadoUpdate($chamado);
        if (!$validationResult->isValid()) {
            return new ChamadoUpdateResponseDTO(
                success: false,
                message: $validationResult->getMessage()
            );
        }

        $changeTracker = $this->changeTrackingService->createTracker($chamado);

        $updateData = $updateDTO->toArray();
        $chamado->update($updateData);

        $changes = $changeTracker->getChanges($updateData);
        if (!empty($changes)) {
            $this->auditService->logChanges($chamado->id, $changes);
        }

        return new ChamadoUpdateResponseDTO(
            success: true,
            message: 'Chamado atualizado com sucesso',
            newData: $updateData,
            changes: $changes,
            chamado: $chamado
        );
    }
}