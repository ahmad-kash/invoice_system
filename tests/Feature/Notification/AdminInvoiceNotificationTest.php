<?php

namespace Tests\Feature\Notification;

use App\Models\Invoice;
use App\Notifications\Database\Invoice\InvoiceCreated;
use App\Notifications\Database\Invoice\InvoiceDeleted;
use App\Notifications\Database\Invoice\InvoiceForceDeleted;
use App\Notifications\Database\Invoice\InvoicePaid;
use App\Notifications\Database\Invoice\InvoicePaidPartially;
use App\Notifications\Database\Invoice\InvoiceRestored;
use App\Notifications\Database\Invoice\InvoiceUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\NotificationTestCase;

class AdminInvoiceNotificationTest extends NotificationTestCase
{
    use RefreshDatabase;

    protected Invoice $invoice;
    public function getUserPermissions(): array
    {
        return [
            'create invoice', 'edit invoice', 'delete invoice', 'restore invoice', 'force delete invoice',
        ];
    }
    public function setUp(): void
    {
        parent::setUp();

        $this->invoice = Invoice::factory()->create();
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_invoice_is_created(): void
    {
        $this->post(route('invoices.store'), $this->invoice->toArray());

        Notification::assertSentTo($this->admins, InvoiceCreated::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_invoice_is_updated(): void
    {
        $this->withoutExceptionHandling();
        $this->put(
            route('invoices.update', ['invoice' => $this->invoice->id]),
            ['number' => '123']
        );

        Notification::assertSentTo($this->admins, InvoiceUpdated::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_invoice_is_deleted_or_restored(): void
    {
        //delete the invoice
        $this->delete(
            route('invoices.destroy', ['invoice' => $this->invoice->id]),
            $this->invoice->toArray()
        );

        Notification::assertSentTo($this->admins, InvoiceDeleted::class);

        //restore the invoice
        $this->put(
            route('invoices.restore', ['invoice' => $this->invoice->id]),
            $this->invoice->toArray()
        );

        Notification::assertSentTo($this->admins, InvoiceRestored::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_invoice_is_force_deleted(): void
    {
        Storage::fake();
        Storage::shouldReceive('directoryExists')
            ->andReturn(true);
        Storage::shouldReceive('deleteDirectory')
            ->andReturn(true);

        $this->delete(
            route('invoices.forceDestroy', ['invoice' => $this->invoice->id]),
            $this->invoice->toArray()
        );

        Notification::assertSentTo($this->admins, InvoiceForceDeleted::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_invoice_is_partially_paid(): void
    {
        $this->post(
            route('invoices.payments.store', ['invoice' => $this->invoice->id]),
            ['amount' => $this->invoice->total - 100]
        );

        Notification::assertSentTo($this->admins, InvoicePaidPartially::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_invoice_is_paid(): void
    {
        $this->post(
            route('invoices.payments.store', ['invoice' => $this->invoice->id]),
            ['amount' => $this->invoice->total]
        );

        Notification::assertSentTo($this->admins, InvoicePaid::class);
    }
}
