<?php

namespace App\Services;

use App\DTOs\ValidationResultDTO;
use App\Models\Chamado;


class ChamadoValidationService
{
    public function canChamadoUpdate(Chamado $chamado): ValidationResultDTO
    {
        if ($chamado->status === 'Finalizado') {
            return new ValidationResultDTO(
                isValid: false,
                message: 'Chamado ja esta finalizado e nao pode ser editado.'
            );
        }
        return new ValidationResultDTO(isValid: true);
    }
}