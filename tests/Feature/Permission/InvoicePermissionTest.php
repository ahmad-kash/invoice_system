<?php

namespace Tests\Feature\Permission;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\PermissionRoleTestFactory;
use Tests\TestCase;

class InvoicePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected User $spyUser;

    public function setUp(): void
    {
        parent::setUp();
        (new PermissionRoleTestFactory)
            ->createPermissions(
                [
                    'create invoice',
                    'delete invoice',
                    'edit invoice',
                    'show invoice',
                    'restore invoice',
                    'force delete invoice',
                ]
            );

        $this->spyUser = $this->spy(User::class);

        $this->signIn($this->spyUser);
    }

    /** @test */
    public function user_is_asked_if_he_has_show_invoice_permission_on_route_invoices_index(): void
    {
        $this->get(route('invoices.index'));

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('show invoice')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_create_invoice_permission_on_route_invoices_store(): void
    {
        $invoice = Invoice::factory()->make();

        $this->post(route('invoices.store'), $invoice->toArray());

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('create invoice')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_edit_invoice_permission_on_route_invoices_update(): void
    {
        $invoice = Invoice::factory()->create();

        $this->put(route('invoices.update', ['invoice' => $invoice->id]), $invoice->toArray());

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('edit invoice')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_delete_invoice_permission_on_route_invoices_delete(): void
    {
        $invoice = Invoice::factory()->create();

        $this->delete(route('invoices.destroy', ['invoice' => $invoice->id]));

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('delete invoice')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_force_delete_invoice_permission_on_route_invoices_force_delete(): void
    {
        $invoice = Invoice::factory()->create();

        $this->delete(route('invoices.forceDestroy', ['invoice' => $invoice->id]));

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('force delete invoice')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_restore_invoice_permission_on_route_invoices_restore(): void
    {
        $invoice = Invoice::factory()->create();

        $this->put(route('invoices.restore', ['invoice' => $invoice->id]));

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('restore invoice')->once();
    }
}
