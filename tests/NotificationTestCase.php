<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase as BaseTestCase;


abstract class NotificationTestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected User $user;

    protected array $admins = [];

    protected int $numberOfAdmins = 2;

    protected PermissionRoleTestFactory $permissionFactory;

    public abstract function getUserPermissions(): array;


    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->permissionFactory = new PermissionRoleTestFactory;

        $this->createAdmins();
        $this->createUser();

        $this->signIn($this->user);
    }
    private function createUser()
    {
        $userPermissions = $this->getUserPermissions();

        $this->permissionFactory->createRoles(['user']);

        //all user permission are already created in create admin method
        $this->permissionFactory->assignPermissionsToRole('user', $userPermissions);

        $this->user = (new UserTestBuilder)->withRoles('user')->create();
    }
    private function createAdmins()
    {
        $adminPermissions = [
            'create invoice', 'edit invoice', 'delete invoice', 'restore invoice', 'force delete invoice',
            'create user', 'edit user', 'delete user', 'reset password', 'force delete user',
            'create section', 'edit section', 'delete section',
            'create product', 'edit product', 'delete product',
            'create role', 'edit role', 'delete role',
        ];

        $this->permissionFactory->createPermissions($adminPermissions);
        $this->permissionFactory->createRoles(['admin']);

        $this->permissionFactory->assignPermissionsToRole('admin', $adminPermissions);

        $this->admins = [];

        for ($i = 0; $i < $this->numberOfAdmins; $i++) {
            $this->admins[] = (new UserTestBuilder)->withRoles('admin')->create();
        }
    }
}
