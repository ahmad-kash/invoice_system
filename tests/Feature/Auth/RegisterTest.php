<?php

namespace Tests\Feature\Auth;

use App\Mail\WelcomeMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\DashboardTestCase;
use Tests\PermissionRoleTestFactory;

class RegisterTest extends DashboardTestCase
{


    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function getPermissions(): array
    {
        return [
            'create user',
        ];
    }

    /** @test */
    public function admin_can_see_create_user_page(): void
    {
        $this->get(route('users.create'))
            ->assertOk()
            ->assertViewIS('user.create')
            ->assertSee('اضافة مستخدم جديد');
    }

    /** @test */
    public function admin_can_create_user(): void
    {
        $user = User::factory()->make();

        $this->post(route('users.store'), $user->toArray() + ['role' => 'admin'])
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['name' => $user->name, 'email' => $user->email]);
        $this->assertDatabaseHas('model_has_roles', ['model_type' => User::class, 'model_id' => 2]);
        Mail::assertSent(WelcomeMail::class);
    }

    /**
     *  @test
     *  @dataProvider provideInvalidDataForUser
     */
    public function admin_can_not_create_user_with_invalid_data(string $fieldName, $name, $email, $is_active, $role): void
    {
        //'admin' is already exists from parent DashboardTestCase
        $roles = (new PermissionRoleTestFactory())->createRoles(['user', 'data entry']);

        $this->post(route('users.store'), ['name' => $name, 'email' => $email, 'is_active' => $is_active, 'role' => $role])
            ->assertSessionHasErrors($fieldName)
            ->assertRedirect();
    }

    public static function provideInvalidDataForUser()
    {
        return [
            'name is null' => static::getFakeDataForValidation('name', null),
            'name is not string' => static::getFakeDataForValidation('name', 5),
            'email is null' => static::getFakeDataForValidation('email', null),
            'email is not email regex' => static::getFakeDataForValidation('email', 'test.2ts'),
            'is_active is not boolean' => static::getFakeDataForValidation('is_active', 'test'),
            'role is null' => static::getFakeDataForValidation('role', null),
            'role is not string' => static::getFakeDataForValidation('role', 5),
            'role does not exists' => static::getFakeDataForValidation('role', 'test'),
        ];
    }

    private static function getFakeDataForValidation($key, $value)
    {
        $faker = self::getFaker();

        $roles  = ['admin', 'user', 'data entry'];

        $fakeData = ['name' => $faker->name(), 'email' => $faker->safeEmail(), 'is_active' => $faker->boolean(), 'role' => $roles[array_rand($roles)]];

        if (is_callable($value))
            $value = $value($fakeData);

        $fakeData[$key] = $value;

        return [$key] + $fakeData;
    }

    /** @test */
    public function guest_can_see_change_password_page(): void
    {
        $user = User::factory()->create();
        $this->logout();

        $this->get(URL::signedRoute('password.reset.create', ['email' => $user->email]))
            ->assertOk()
            ->assertSee('كلمة المرور');
    }

    /** @test */
    public function guest_can_change_his_password_and_confirm_his_email(): void
    {
        $this->logout();
        $user = User::factory()->create();

        $this->post(URL::signedRoute('password.reset.store', ['email' => $user->email]))
            ->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', ['email' => $user->email, 'email_verified_at' => Date::now()]);
    }

    protected function logout()
    {
        auth()->logout();
        $this->assertGuest();
    }
}
