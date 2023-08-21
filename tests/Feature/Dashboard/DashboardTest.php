<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function auth_user_can_see_dashboard(): void
    {
        $this->signin()->get(route('home'))->assertSee('Dashboard')->assertOk();
    }
    /** @test */
    public function guest_can_not_see_dashboard(): void
    {
        $this->get(route('home'))->assertRedirectToRoute('login.create');
    }
}
