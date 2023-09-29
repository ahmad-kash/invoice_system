<?php

namespace Tests\Feature\Dashboard;

use App\Models\Invoice;
use Tests\DashboardTestCase;

class InvoiceArchiveTest extends DashboardTestCase
{

    public function getPermissions(): array
    {
        return
            [
                'create invoice',
                'show invoice',
                'delete invoice',
                'edit invoice',
                'force delete invoice',
                'restore invoice',
            ];
    }


    /** @test */
    public function user_can_soft_delete_invoice(): void
    {
        $invoice = Invoice::factory()->create();

        $this->delete(route('invoices.destroy', ['invoice' => $invoice->id]))
            ->assertRedirect();

        $this->assertDatabaseHas('invoices', ['number' => $invoice->number, 'deleted_at' => now()]);
    }

    /** @test */
    public function user_can_restore_soft_deleted_invoice(): void
    {
        $invoice = Invoice::factory()->create();
        $this->withoutExceptionHandling();

        $this->delete(route('invoices.destroy', ['invoice' => $invoice->id]));
        $this->put(route('invoices.restore', ['invoice' => $invoice->id]))
            ->assertRedirect();
        $this->assertDatabaseHas('invoices', ['number' => $invoice->number, 'deleted_at' => null]);
    }

    /** @test */
    public function user_can_see_archive_invoice_page(): void
    {
        $invoices = Invoice::factory(2)->create();
        $archivedInvoices = Invoice::factory(2)->create(['deleted_at' => now()]);

        $this->get(route('invoices.archive.index'))
            ->assertOk()
            ->assertViewHas('invoices', function ($paginator) use ($archivedInvoices) {
                return $this->eloquentCollectionsAreEqual(collect($paginator->items()), $archivedInvoices);
            })
            ->assertSeeInOrder($archivedInvoices->map(fn ($invoice) => $invoice->number)->toArray());
    }
}
