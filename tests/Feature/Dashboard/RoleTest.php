<?php

namespace Tests\Feature\Dashboard;

use App\Services\Role\RoleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\DashboardTestCase;

class RoleTest extends DashboardTestCase
{
    public function getPermissions(): array
    {
        return [
            'create role',
            'edit role',
            'show role',
            'update role',
            'delete role',
        ];
    }

    /** @test */
    public function user_can_see_role_index_page(): void
    {
        $this->get(route('roles.index'))
            ->assertViewIs('role.index')
            ->assertViewHas('roles');
    }

    /** @test */
    public function user_can_see_create_role_page(): void
    {
        $this->get(route('roles.create'))
            ->assertViewIs('role.create');
    }

    /** @test */
    public function user_can_see_edit_role_page(): void
    {
        $role = Role::create(['name' => 'user']);

        $this->get(route('roles.edit', ['role' => $role->id]))
            ->assertViewIs('role.edit')
            ->assertViewHas('role', $role);
    }

    /** @test */
    public function user_can_create_role(): void
    {
        $roleData = [
            'name' => 'user',
        ];
        $permissions = [
            'create role',
            'edit role',
            'show role',
            'update role',
            'delete role',
        ];
        $this->post(route('roles.store'), $roleData + ['permissions' => $permissions])
            ->assertRedirect(route('roles.index'));

        $this->assertDatabaseHas('roles', $roleData);
    }

    /** @test */
    public function user_can_edit_role(): void
    {
        $role = Role::create(['name' => 'user']);
        $permissions = [
            'create role',
            'edit role',
            'show role',
            'update role',
            'delete role',
        ];
        $this->put(route('roles.update', ['role' => $role->id]), ['name' => 'new name', 'permissions' => $permissions])
            ->assertRedirect(route('roles.index'));

        $this->assertDatabaseHas('roles', ['name' => 'new name']);
    }

    /** @test */
    public function user_can_see_all_permissions(): void
    {
        $permissions = app(RoleService::class)->getPermissions();

        // in create page
        $this->get(route('roles.create'))
            ->assertOk()
            ->assertViewHas('permissions', $permissions);

        // in edit page
        $role = Role::create(['name' => 'test']);
        $this->get(route('roles.edit', ['role' => $role->id]))
            ->assertOk()
            ->assertViewHas('permissions', $permissions);
    }

    /**
     * @test
     * @dataProvider provideInvalidDataForRoleCreation
     *
     */
    public function user_can_not_create_role_with_invalid_data(string $fieldName, $name, $permissions): void
    {
        $this->post(route('roles.store'), ['name' => $name, 'permissions' => $permissions])
            ->assertSessionHasErrors($fieldName)
            ->assertRedirect();
    }

    public static function provideInvalidDataForRoleCreation()
    {

        return [
            'name is null' => static::getFakeDataForValidation('name', null),
            'name is not string' => static::getFakeDataForValidation('name', 1),
            'name is not unique' => static::getFakeDataForValidation('name', 'admin'),

            'permissions is null' => static::getFakeDataForValidation('permissions', null),
            'permissions is not an array' => static::getFakeDataForValidation('permissions', 'test'),
            'permissions.* does not exists' => static::getFakeDataForValidation('permissions', ['test'], 'permissions.*'),
        ];
    }

    /**
     * @test
     * @dataProvider provideInvalidDataForRoleEdition
     *
     */
    public function user_can_not_edit_role_with_invalid_data(string $fieldName, $name, $permissions): void
    {
        $role = Role::create(['name' => 'test']);
        $this->put(route('roles.update', ['role' => $role->id]), ['name' => $name, 'permissions' => $permissions])
            ->assertSessionHasErrors($fieldName)
            ->assertRedirect();
    }

    public static function provideInvalidDataForRoleEdition()
    {
        return [
            'name is null' => static::getFakeDataForValidation('name', null),
            'name is not string' => static::getFakeDataForValidation('name', 1),
            "name does not exists with the same id and name and it name is not unique" => static::getFakeDataForValidation('name', 'admin'),

            'permissions is null' => static::getFakeDataForValidation('permissions', null),
            'permissions is not an array' => static::getFakeDataForValidation('permissions', 'test'),
            'permissions.* does not exists' => static::getFakeDataForValidation('permissions', ['test'], 'permissions.*'),
        ];
    }

    private static function getFakeDataForValidation($key, $value, $fieldName = null)
    {
        $faker = self::getFaker();

        $fakeData = ['name' => $faker->name(), 'permissions' => ['create role', 'edit role', 'delete role', 'show role']];

        if (is_callable($value))
            $value = $value($fakeData);

        $fakeData[$key] = $value;

        $data = is_null($fieldName) ? [$key] + $fakeData : [$fieldName] + $fakeData;

        return $data;
    }

    /** @test */
    public function user_can_delete_role(): void
    {
        $role = Role::create(['name' => 'user']);
        $this->delete(route('roles.destroy', ['role' => $role->id]))
            ->assertRedirect(route('roles.index'));

        $this->assertDatabaseMissing('roles', ['name' => 'user']);
    }
}
