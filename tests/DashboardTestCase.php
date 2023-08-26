<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use Mockery;
use Mockery\MockInterface;

abstract class DashboardTestCase extends BaseTestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected User $user;

    public abstract function getPermissions(): array;

    public function setUp(): void
    {
        parent::setUp();
        $permissions = $this->getPermissions();

        $permissionFactory = new PermissionRoleTestFactory;
        $permissionFactory->createPermissions($permissions);
        $permissionFactory->createRoles(['admin']);
        $permissionFactory->assignPermissionsToRole('admin', $permissions);

        $this->user = (new UserTestBuilder)->withRoles('admin')->create();
        $this->signIn($this->user);
    }

    public static function getFaker()
    {
        $obj = (new static('test'));
        $obj->createApplication();
        $obj->setUpFaker();
        return $obj->faker;
    }
}
