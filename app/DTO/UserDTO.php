<?php

namespace App\DTO;

use App\DTO\Interfaces\DTOInterface;
use App\Models\User;

readonly class UserDTO implements DTOInterface
{

    public function __construct(
        private string $name,
        private string $email,
        private bool $is_active,
        private ?string $password,
    ) {
    }

    public static function fromUpdateRequest(array $userData, User $user): self
    {
        return new self(
            name: $userData['name'] ?? $user->name,
            email: $userData['email'] ?? $user->email,
            is_active: $userData['is_active'] ?? $user->is_active,
            password: null,
        );
    }
    public function toUpdateArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ];
    }
    public static function fromArray(array $userData): self
    {
        return new self(
            name: $userData['name'],
            email: $userData['email'],
            is_active: $userData['is_active'] ?? false,
            password: $userData['password'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'password' => $this->password,
        ];
    }
}
