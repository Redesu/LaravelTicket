<?php
namespace App\Services\UserManagement;

use App\DTOs\UserManagement\Requests\UpdateUserRequestDTO;
use App\DTOs\UserManagement\Responses\UserResponseDTO;
use App\Models\User;
use DB;
use Hash;
use Log;
use Storage;

class UserUpdateService
{
    public function updateUser(UpdateUserRequestDTO $DTO, int $id): UserResponseDTO
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);

            if ($DTO->hasAvatar()) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $avatarPath = $DTO->getAvatar()->store('avatars', 'public');
                $user->avatar = $avatarPath;
            }

            if ($DTO->getName()) {
                $user->name = $DTO->getName();
            }

            if ($DTO->getEmail()) {
                $user->email = $DTO->getEmail();
            }

            if ($DTO->getPassword()) {
                $user->password = Hash::make($DTO->getPassword());
            }

            $user->save();
            DB::commit();
            return UserResponseDTO::success(
                data: $user->toArray(),
                message: 'Usuário atualizado com sucesso.'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar usuário: ' . $e->getMessage());
            return UserResponseDTO::error(
                message: 'Erro ao atualizar usuário.',
                error: 'Ocorreu um erro ao atualizar o usuário. Por favor, tente novamente.'
            );
        }
    }
}