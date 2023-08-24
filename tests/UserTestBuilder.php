<?php

namespace Tests;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Mockery\Mock;
use Mockery\MockInterface;

class UserTestBuilder
{
    private User $user;
    private array $roles = [];
    private array $permissions = [];

    public function create(array|Collection|User|MockInterface $userData = []): User
    {
        if ($userData instanceof User || $userData instanceof MockInterface)
            $this->user = $userData;
        else
            $this->user = User::factory()->create($userData);

        $this->assignPermissionsAndRoles();

        return $this->user;
    }
    protected function assignPermissionsAndRoles()
    {
        // assign roles
        if ($this->roles)
            $this->user->syncRoles($this->roles);

        // assign permissions
        if ($this->permissions)
            $this->user->syncPermissions($this->permissions);
    }
    public function withPermissions(array|string $permissions): self
    {
        $this->permissions = Arr::wrap($permissions);

        return $this;
    }
    public function withRoles(array|string $roles): self
    {
        $this->roles = Arr::wrap($roles);

        return $this;
    }
}
