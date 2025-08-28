<?php
namespace App\DTOs;

use Illuminate\Http\JsonResponse;

class GetEstatisticasResponseDTO
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $qntdNovosChamados = null,
        public readonly ?string $porcentagemChamadosFechados = null,
        public readonly ?int $qntdChamadosUrgentes = null,
        public readonly ?int $qntdUsuarios = null,
        public readonly ?string $message = null,
        public readonly ?string $error = null
    ) {
    }

    public static function success(
        $qntdNovosChamados,
        $porcentagemChamadosFechados,
        $qntdChamadosUrgentes,
        $qntdUsuarios
    ): self {
        return new self(
            success: true,
            qntdNovosChamados: $qntdNovosChamados,
            porcentagemChamadosFechados: $porcentagemChamadosFechados,
            qntdChamadosUrgentes: $qntdChamadosUrgentes,
            qntdUsuarios: $qntdUsuarios
        );
    }

    public static function error(string $message, string $error): self
    {
        return new self(
            success: false,
            message: $message,
            error: $error
        );
    }


    public function toArray(): array
    {
        return [
            'qntdNovosChamados' => $this->qntdNovosChamados,
            'porcentagemChamadosFechados' => $this->porcentagemChamadosFechados,
            'qntdChamadosUrgentes' => $this->qntdChamadosUrgentes,
            'qntdUsuarios' => $this->qntdUsuarios
        ];
    }

    public function toJsonResponse(?int $statusCode = null): JsonResponse
    {
        $code = $statusCode ?? ($this->success ? 200 : 500);
        return response()->json($this->toArray(), $code);
    }

}