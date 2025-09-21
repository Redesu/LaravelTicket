<?php

namespace App\Http\Controllers;

use App\DTOs\UserManagement\Requests\UpdateUserRequestDTO;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserManagement\UserUpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserUpdateService $userUpdateService
    ) {
    }
    public function showUserSettings()
    {
        $user = User::find(auth()->user()->id);
        return view('admin.settings', compact('user'));
    }

    public function updateUserSettings(UpdateUserRequest $request, $id): JsonResponse
    {
        $updateUserRequest = UpdateUserRequestDTO::fromRequest($request);
        $result = $this->userUpdateService->updateUser($updateUserRequest, $id);

        return $result->toJsonResponse();
    }
}
