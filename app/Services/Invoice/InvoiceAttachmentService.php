<?php

namespace App\Services\Invoice;

use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Services\Interfaces\FilesUploaderInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use SplFileInfo;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceAttachmentService
{

    public function __construct(private FilesUploaderInterface $filesUploader)
    {
    }

    public function getAllForInvoice(Invoice $invoice): EloquentCollection
    {
        return InvoiceAttachment::where('invoice_id', $invoice->id)->get();
    }

    public function store(Invoice $invoice, Null|array|Collection|SplFileInfo $files): bool
    {
        $filesData = $this->filesUploader->upload($invoice->directory, $files);
        foreach ($filesData as $data) {
            InvoiceAttachment::create($this->getDataToStore($invoice, $data));
        }
        return true;
    }

    public function show(string $path): BinaryFileResponse
    {
        return response()->file(storage_path('app/' . $path));
    }

    public function download(string $path): StreamedResponse
    {
        return $this->filesUploader->download($path);
    }

    public function delete(InvoiceAttachment $attachment): bool
    {
        $this->filesUploader->delete($attachment->path);

        return $attachment->delete();
    }

    public function deleteAll(Invoice $invoice): bool
    {
        $this->filesUploader->deleteDirectory($invoice->directory);

        return InvoiceAttachment::where('invoice_id', $invoice->id)->delete();
    }

    private function getDataToStore(Invoice $invoice, array $fileData): array
    {
        return [
            'invoice_id' => $invoice->id, 'user_id' => auth()->id(),
            'hash_name' => $fileData['hash_name'], 'name' => $fileData['name']
        ];
    }
}
