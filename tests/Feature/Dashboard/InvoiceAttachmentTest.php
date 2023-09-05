<?php

namespace Tests\Feature\Dashboard;

use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Services\Invoice\InvoiceAttachmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\DashboardTestCase;


class InvoiceAttachmentTest extends DashboardTestCase
{

    use RefreshDatabase;

    public function getPermissions(): array
    {
        //user whom can create or show an invoice can create or show an attachment
        return
            [
                'create invoice',
                'show invoice',
                'delete invoice',
            ];
    }

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake();
    }

    /** @test */
    public function user_can_create_attachment(): void
    {
        $invoice = Invoice::factory()->create();

        $file = UploadedFile::fake()->image('test1.jpg');

        $this->post(route('invoices.attachments.store', ['invoice' => $invoice->id]), ['file' => $file])
            ->assertRedirect(route('invoices.show', ['invoice' => $invoice->id]));
        $this->assertDatabaseHas('invoice_attachments', ['name' => $file->getClientOriginalName()]);

        Storage::assertExists(app()->make(InvoiceAttachmentService::class)->getFileDirPath($invoice) . '/' . $file->hashName());
    }

    /** @test */
    public function user_can_delete_attachment(): void
    {
        [$invoice, $invoiceAttachment] = $this->getInvoiceWithAnAttachment();

        $this->withoutExceptionHandling();

        $invoiceAttachmentHashName = $invoiceAttachment->hash_name;

        $this->delete(route('invoices.attachments.destroy', ['attachment' => $invoiceAttachment->id]))
            ->assertRedirect(route('invoices.show', ['invoice' => $invoice->id]));

        $this->assertDatabaseMissing('invoice_attachments', ['hash_name' => $invoiceAttachmentHashName]);
        Storage::assertMissing($invoiceAttachmentHashName);
    }

    /** @test */
    public function user_can_show_attachment(): void
    {
        [$invoice, $invoiceAttachment] = $this->getInvoiceWithAnAttachment();

        $this->get(route('invoices.attachments.show', ['attachment' => $invoiceAttachment->id]))
            ->assertContent(Storage::get($invoiceAttachment->path));
    }

    /** @test */
    public function user_can_download_attachment(): void
    {
        [$invoice, $invoiceAttachment, $file] = $this->getInvoiceWithAnAttachment();

        $this->get(route('invoices.attachments.download', ['attachment' => $invoiceAttachment->id]))
            ->assertDownload($file->hashName());
    }

    private function getInvoiceWithAnAttachment()
    {
        $invoice = Invoice::factory()->create();
        //create file and store it
        $file = UploadedFile::fake()->image('test.jpg');
        app()->make(InvoiceAttachmentService::class)->store($invoice, $file);

        $invoiceAttachment = app()->make(InvoiceAttachmentService::class)->getAllForInvoice($invoice)[0];

        return [$invoice, $invoiceAttachment, $file];
    }
}
