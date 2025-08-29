<?php
namespace App\DTOs\ChamadoManagement\Requests;

use App\Http\Requests\DeleteChamadoRequests;
use Auth;


class DeleteChamadoRequestDTO
{
    private int $chamadoId;
    private int $user_id;

    public function __construct(int $chamadoId, int $user_id)
    {
        $this->chamadoId = $chamadoId;
        $this->user_id = $user_id;
    }

    public function create(int $chamadoId, int $user_id): self
    {
        return new self($chamadoId, $user_id);
    }

    public static function fromRequest(DeleteChamadoRequests $request): self
    {
        $validatedData = $request->validated();
        return new self($validatedData['id'], Auth::id());
    }

    public function getChamadoId(): int
    {
        return $this->chamadoId;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }
}