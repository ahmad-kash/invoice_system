<?php

namespace Tests\Feature\Permission;

use App\Models\InvoiceAttachment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\PermissionRoleTestFactory;
use Tests\TestCase;

class InvoiceAttachmentPermissionTest extends TestCase
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
                    'show invoice',
                ]
            );

        $this->spyUser = $this->spy(User::class);

        $this->signIn($this->spyUser);
    }

    /** @test */
    public function user_is_asked_if_he_has_show_invoice_permission_on_route_invoices_attachment_show(): void
    {
        $invoiceAttachment = InvoiceAttachment::factory()->create();

        $this->get(route('invoices.attachments.show', ['attachment' => $invoiceAttachment->id]));

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('show invoice')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_create_invoice_permission_on_route_invoices_attachment_store(): void
    {
        $invoiceAttachment = InvoiceAttachment::factory()->make();

        $this->post(route('invoices.attachments.store', ['invoice' => $invoiceAttachment->invoice]), $invoiceAttachment->toArray());

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('create invoice')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_delete_invoice_permission_on_route_invoices_attachment_delete(): void
    {
        $invoiceAttachment = InvoiceAttachment::factory()->create();

        $this->delete(route('invoices.attachments.destroy', ['attachment' => $invoiceAttachment->id]));

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('delete invoice')->once();
    }


    /** @test */
    public function user_is_asked_if_he_has_restore_invoice_permission_on_route_invoices_attachment_download(): void
    {
        $invoiceAttachment = InvoiceAttachment::factory()->create();

        $this->get(route('invoices.attachments.download', ['attachment' => $invoiceAttachment->id]));

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('show invoice')->once();
    }
}
