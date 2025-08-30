<?php
namespace App\Services\ChamadoManagement;

use App\DTOs\ChamadoManagement\Requests\UpdateChamadoRequestDTO;
use App\DTOs\ChamadoManagement\Responses\UpdateChamadoResponseDTO;
use App\Models\Chamado;
use App\Services\ChamadoManagement\Validation\ChamadoValidationService;
use App\Services\ChamadoTracking\ChamadoAuditService;
use App\Services\ChamadoTracking\ChamadoChangeTrackingService;
use Auth;
use Log;

class ChamadoUpdateService
{
    public function __construct(
        private ChamadoValidationService $validationService,
        private ChamadoChangeTrackingService $changeTrackingService,
        private ChamadoAuditService $auditService
    ) {
    }

    public function updateChamado(UpdateChamadoRequestDTO $updateDTO, int $id): UpdateChamadoResponseDTO
    {
        try {
            $chamado = Chamado::with('categoria', 'departamento', 'usuario')->findOrFail($id);

            $validationResult = $this->validationService->canChamadoUpdate($chamado);
            if (!$validationResult->isValid()) {
                return new UpdateChamadoResponseDTO(
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

            return UpdateChamadoResponseDTO::success(
                message: 'Chamado atualizado com sucesso',
                newData: $updateData,
                changes: $changes,
                chamado: $chamado
            );
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar chamado: ' . $e->getMessage());
            return UpdateChamadoResponseDTO::error(
                message: 'Erro ao atualizar chamado',
                error: 'Ocorreu um erro ao atualizar o chamado.'
            );
        }
    }
}
