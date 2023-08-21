<?php

namespace Tests\Feature\Auth;

use App\Http\Kernel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function guest_can_see_login_page(): void
    {
        $this->get(route('login.create'))->assertOk();
    }

    public static function provideRouteListExceptLogin(): array
    {
        $app = (new self('test'))->createApplication();

        $laravelDefualtRoutes = ['sanctum/csrf-cookie', '_ignition/health-check', '_ignition/execute-solution', '_ignition/update-config'];

        $routeExceptList = [...$laravelDefualtRoutes, 'login'];

        $routeList = [];
        foreach ($app->make(Router::class)->getRoutes() as $route)
            // get just the user defined route that has GET http method
            if (!in_array($route->uri(), $routeExceptList) && in_array('GET', $route->methods()))
                $routeList[$route->uri()] = [$route->uri()];
        return $routeList;
    }

    /**
     * @test
     * @dataProvider provideRouteListExceptLogin
     */

    public function guest_can_not_see_any_page_except_login(string $routeUrl): void
    {
        $this->get($routeUrl)->assertRedirectToRoute('login.create');
    }

    /** @test */
    public function auth_user_can_not_see_login_page(): void
    {
        $this->signIn()->get(route('login.create'))->assertRedirectToRoute('home');
    }

    /** @test */
    public function guest_can_login(): void
    {
        $user = User::factory()->create(['email' => 'test@test.com', 'password' => bcrypt('123456789')]);
        $this->post(route('login.store'), ['email' => 'test@test.com', 'password' => '123456789'])->assertRedirectToRoute('home');
    }
}
