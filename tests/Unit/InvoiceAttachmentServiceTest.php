<?php

namespace Tests\Unit;

use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Services\Interfaces\FilesUploaderInterface;
use App\Services\Invoice\InvoiceAttachmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use tests\TestCase;

class InvoiceAttachmentServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_retrieve_all_attachment_for_an_invoice(): void
    {
        $invoice = Invoice::factory()->create();
        $invoiceAttachments = InvoiceAttachment::factory(5)->create();

        $this->assertEloquentCollectionsEqual(app(InvoiceAttachmentService::class)->getAllForInvoice($invoice), $invoiceAttachments);
    }

    /** @test */
    public function it_can_store_attachments_to_an_invoice(): void
    {

        $invoice = Invoice::factory()->create();
        $file = UploadedFile::fake()->image('file.jpg');
        $returnedData = ['hash_name' => $file->hashName(), 'name' => $file->getClientOriginalName()];
        $this->signIn();

        $this->mock(FilesUploaderInterface::class, function (MockInterface $mock) use ($invoice, $file, $returnedData) {
            $mock->shouldReceive('upload')
                ->with($invoice->getDirectory(), $file)
                ->andReturn([$returnedData]);
        });

        app(InvoiceAttachmentService::class)->store($invoice, $file);

        $this->assertDatabaseHas('invoice_attachments', $returnedData);
    }

    /** @test */
    public function it_can_show_a_file(): void
    {
        $this->mock(FilesUploaderInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->with('path')
                ->andReturn('some file path');
        });

        $this->assertEquals('some file path', app(InvoiceAttachmentService::class)->show('path'));
    }

    /** @test */
    public function it_can_download_a_file(): void
    {

        $this->mock(FilesUploaderInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('download')
                ->with('path')
                ->andReturn(new StreamedResponse());
        });

        $this->assertEquals(new StreamedResponse(), app(InvoiceAttachmentService::class)->download('path'));
    }

    /** @test */
    public function it_can_delete_an_attachment(): void
    {
        $invoiceAttachment = InvoiceAttachment::factory()->create();
        $this->mock(FilesUploaderInterface::class, function (MockInterface $mock) use ($invoiceAttachment) {
            $mock->shouldReceive('delete')
                ->with($invoiceAttachment->path);
        });

        app(InvoiceAttachmentService::class)->delete($invoiceAttachment);

        $this->assertDatabaseEmpty('invoice_attachments');
    }

    /** @test */
    public function it_can_delete_all_the_attachment_for_an_invoice(): void
    {
        $invoice = Invoice::factory()->create();
        $invoiceAttachments = InvoiceAttachment::factory(5)->create(['invoice_id' => $invoice->id]);
        $this->mock(FilesUploaderInterface::class, function (MockInterface $mock) use ($invoice) {
            $mock->shouldReceive('deleteDirectory')
                ->with($invoice->getDirectory())
                ->andReturn(true);
        });

        app(InvoiceAttachmentService::class)->deleteAll($invoice);

        $this->assertDatabaseEmpty('invoice_attachments');
    }
}
