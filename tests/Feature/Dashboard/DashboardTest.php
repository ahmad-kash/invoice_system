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
    public function user_can_see_statistic(): void
    {
        $this->signIn();
        $this->get(route('home'))
            ->assertOk()
            ->assertViewHasAll([
                'totalInvoicesCount',
                'paidInvoicesCount',
                'unPaidInvoicesCount',
                'partiallyPaidInvoicesCount',
                'totalInvoicesSum',
                'paidInvoicesSum',
                'unPaidInvoicesSum',
                'partiallyPaidInvoicesSum'
            ]);
    }

    /** @test */
    public function non_admin_user_can_not_see_notification_icon(): void
    {
        $this->signIn();
        $this->get(route('home'))
            ->assertDontSee('<a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fa fa-bell"></i>
                <span class="badge badge-warning navbar-badge">', false);
    }

    /** @test */
    public function un_active_user_must_see_user_is_not_active_page(): void
    {
        $user = User::factory()->create(['is_active' => false] + ['email' => 'test@test.com', 'password' => bcrypt('123456789')]);
        $this->signIn($user);

        $this->get(route('home'))
            ->assertRedirectToRoute('isnotactive');

        $this->followingRedirects();

        $this->get(route('home'))
            ->assertSee('المستخدم غير مفعل يرجى مراجعة احد المشرفين');
    }
}
