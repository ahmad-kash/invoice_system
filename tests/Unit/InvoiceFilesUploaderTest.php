<?php

namespace Tests\Unit;

use App\Models\Invoice;
use App\Services\Invoice\InvoiceFilesUploader;
use App\Services\UploadFileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use TypeError;

class InvoiceFilesUploaderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_upload_multiple_files(): void
    {
        $file1 = UploadedFile::fake()->image('test1.jpg');
        $file2 = UploadedFile::fake()->image('test2.jpg');
        $invoice = Invoice::factory()->create();

        Storage::shouldReceive('put')
            ->once()
            ->with($invoice->section->name . '/' . $invoice->number . '/' . $file1->hashName(), $file1);


        Storage::shouldReceive('put')
            ->once()
            ->with($invoice->section->name . '/' . $invoice->number . '/' . $file2->hashName(), $file2);

        (new InvoiceFilesUploader([$file1, $file2]))->execute($invoice);
    }

    /** @test */
    public function it_throw_an_exception_if_the_data_is_not_a_valid_file(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The data should be a valid file');
        (new InvoiceFilesUploader(['test']));
    }
    /** @test */
    public function it_throw_an_exception_if_the_data_passed_not_valid(): void
    {
        $this->expectException(TypeError::class);
        (new InvoiceFilesUploader('test'));
    }
}
