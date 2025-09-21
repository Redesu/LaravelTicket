<?php
namespace App\DTOs\UserManagement\Requests;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\UploadedFile;

class UpdateUserRequestDTO
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $email,
        public readonly ?string $password,
        public readonly ?UploadedFile $avatar
    ) {
    }

    public static function fromRequest(UpdateUserRequest $request): self
    {
        $validatedData = $request->validated();
        return new self(
            name: $validatedData['name'] ?? null,
            email: $validatedData['email'] ?? null,
            password: $validatedData['password'] ?? null,
            avatar: $validatedData['avatar'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'avatar' => $this->avatar,
        ];
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getAvatar(): ?UploadedFile
    {
        return $this->avatar;
    }

    public function hasAvatar(): bool
    {
        return $this->avatar !== null;
    }
}